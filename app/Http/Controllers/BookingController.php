<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Mail\BookingConfirmationMail;
use App\Mail\BookingCancellationMail;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    /**
     * Display booking creation form.
     */
    public function create(Tour $tour)
    {
        // Check if tour is available
        if (!$tour->isAvailable()) {
            return redirect()->route('tours.show', $tour)
                ->with('error', 'Tour này hiện không khả dụng để đặt.');
        }

        return view('bookings.create', compact('tour'));
    }

    /**
     * Store a newly created booking.
     */
    public function store(Request $request, Tour $tour)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'adults' => 'required|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'infants' => 'nullable|integer|min:0',
            'special_requests' => 'nullable|string|max:1000',
        ]);

        // Calculate total people
        $totalPeople = ($validated['adults'] ?? 0) + ($validated['children'] ?? 0) + ($validated['infants'] ?? 0);

        if ($totalPeople < 1) {
            return back()->withErrors(['adults' => 'Phải có ít nhất 1 người đặt tour.']);
        }

        // Check if tour is available
        if (!$tour->isAvailable()) {
            return back()->with('error', 'Tour này hiện không khả dụng để đặt.');
        }
        
        DB::beginTransaction();

        try {
            // Double-check available slots with lock to prevent race condition
            // Use FOR UPDATE lock to prevent concurrent bookings
            $currentMaxPeople = DB::table('tours')
                ->where('id', $tour->id)
                ->lockForUpdate()
                ->value('max_people');

            if ($currentMaxPeople !== null && $currentMaxPeople < $totalPeople) {
                throw new \Exception('Tour không đủ chỗ trống.');
            }

            // Create booking
            $booking = Booking::create([
                'user_id' => auth()->id(),
                'tour_id' => $tour->id,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'adults' => $validated['adults'] ?? 0,
                'children' => $validated['children'] ?? 0,
                'infants' => $validated['infants'] ?? 0,
                'total_people' => $totalPeople,
                'special_requests' => $validated['special_requests'] ?? null,
                'status' => 'pending',
                'total_amount' => $this->calculateTotalAmount($tour, $validated),
            ]);

            // Decrement max_people to reserve slots (prevent race condition)
            if ($currentMaxPeople !== null) {
                $tour->decrement('max_people', $totalPeople);
            }

            // Log activity
            \App\Models\ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'created_booking',
                'description' => "Đã tạo đặt chỗ #{$booking->booking_code} cho tour {$tour->name}",
                'properties' => [
                    'booking_id' => $booking->id,
                    'tour_id' => $tour->id,
                    'tour_name' => $tour->name,
                    'total_amount' => $booking->total_amount,
                    'reserved_slots' => $totalPeople,
                ],
            ]);

            DB::commit();

            // Send confirmation email (optional)
            try {
                Mail::to($booking->email)->send(new BookingConfirmationMail($booking));
            } catch (\Exception $e) {
                // Log error but don't fail the booking
                \Log::error('Failed to send booking confirmation email: ' . $e->getMessage());
            }

            // Redirect to payment
            return redirect()->route('payments.show', $booking)
                ->with('success', 'Đặt tour thành công! Vui lòng hoàn tất thanh toán.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Booking creation failed: ' . $e->getMessage());
            
            return back()->with('error', 'Có lỗi xảy ra khi đặt tour. Vui lòng thử lại.');
        }
    }

    /**
     * Display the specified booking.
     */
    public function show(Booking $booking)
    {
        // Check authorization
        if ($booking->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $booking->load(['tour.destination', 'tour.images', 'payments']);

        // Check if user can write review
        $canReview = false;
        if (auth()->check() && !auth()->user()->isAdmin()) {
            $hasBooking = $booking->isPaid() && $booking->status === 'confirmed';
            $hasReviewed = auth()->user()->reviews()
                ->where('tour_id', $booking->tour_id)
                ->exists();
            $canReview = $hasBooking && !$hasReviewed;
        }

        return view('bookings.show', compact('booking', 'canReview'));
    }

    /**
     * Display booking success page.
     */
    public function success(Booking $booking)
    {
        // Check authorization
        if ($booking->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        if (!$booking->isPaid()) {
            return redirect()->route('bookings.show', $booking)
                ->with('error', 'Booking chưa được thanh toán.');
        }

        $booking->load(['tour.destination', 'tour.images', 'payments']);

        return view('bookings.success', compact('booking'));
    }

    /**
     * Display user's booking history.
     */
    public function history(Request $request)
    {
        $query = Booking::where('user_id', auth()->id())
            ->with(['tour.destination', 'tour.primaryImage', 'payments']);

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Search by booking code or tour name
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                  ->orWhereHas('tour', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $bookings = $query->latest()->paginate(10)->withQueryString();

        // Get user's reviewed tour IDs
        $reviewedTourIds = auth()->user()->reviews()->pluck('tour_id')->toArray();

        return view('bookings.history', compact('bookings', 'reviewedTourIds'));
    }

    /**
     * Cancel a booking.
     */
    public function cancel(Request $request, Booking $booking)
    {
        // Check authorization
        if ($booking->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if booking can be cancelled
        if (!$booking->canCancel()) {
            return back()->with('error', 'Không thể hủy đặt chỗ này.');
        }

        DB::beginTransaction();

        try {
            $booking->cancel();

            DB::commit();

            // Send cancellation email
            try {
                Mail::to($booking->email)->send(new BookingCancellationMail($booking));
            } catch (\Exception $e) {
                \Log::error('Failed to send cancellation email: ' . $e->getMessage());
            }

            return redirect()->route('bookings.show', $booking)
                ->with('success', 'Đã hủy đặt chỗ thành công.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Booking cancellation failed: ' . $e->getMessage());
            
            return back()->with('error', 'Có lỗi xảy ra khi hủy đặt chỗ. Vui lòng thử lại.');
        }
    }

    /**
     * Calculate total amount for booking.
     */
    private function calculateTotalAmount(Tour $tour, array $data)
    {
        $total = 0;
        
        $total += ($data['adults'] ?? 0) * $tour->price_adult;
        $total += ($data['children'] ?? 0) * $tour->price_child;
        $total += ($data['infants'] ?? 0) * $tour->price_infant;

        return $total;
    }
}
