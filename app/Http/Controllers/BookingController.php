<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\ActivityLog;
use App\Models\Booking;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Mail\BookingConfirmationMail;
use App\Mail\BookingCancellationMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    /**
     * Display booking creation form.
     */
    public function create(Tour $tour)
    {
        // Check if tour is available (general check)
        if (!$tour->isAvailable()) {
            return redirect()->route('tours.show', $tour)
                ->with('error', 'Tour này hiện không khả dụng để đặt.');
        }

        return view('bookings.create', compact('tour'));
    }

    /**
     * Store a newly created booking.
     */
    public function store(StoreBookingRequest $request, Tour $tour)
    {
        $validated = $request->validated();
        $totalPeople = $request->totalPeople();

        if ($totalPeople < 1) {
            return back()->withErrors(['adults' => 'Phải có ít nhất 1 người đặt tour.']);
        }

        // Check if tour is available
        if (!$tour->isAvailable()) {
            return back()->with('error', 'Tour này hiện không khả dụng để đặt.');
        }

        $startDate = $validated['start_date'];
        
        // Calculate end date based on duration
        // Note: duration_days includes start day, so we add duration_days - 1
        // Example: 2 days tour starting 01/01 -> ends 02/01. 01/01 + 1 day.
        $endDate = \Carbon\Carbon::parse($startDate)->addDays($tour->duration_days - 1)->format('Y-m-d');
        
        DB::beginTransaction();

        try {
            // Double-check available slots with lock to prevent race condition
            // Use FOR UPDATE lock to prevent concurrent bookings
            $tourLocked = DB::table('tours')
                ->where('id', $tour->id)
                ->lockForUpdate()
                ->first(['id', 'max_people', 'name', 'price_adult', 'price_child', 'price_infant']);

            // Calculate required slots (exclude free participants)
            $requiredSlots = 0;
            if ($tour->price_adult > 0) $requiredSlots += $validated['adults'];
            if ($tour->price_child > 0) $requiredSlots += ($validated['children'] ?? 0);
            if ($tour->price_infant > 0) $requiredSlots += ($validated['infants'] ?? 0);

            if (!$this->checkAvailability($tour, $startDate, $requiredSlots)) {
                 throw new \Exception("Tour vào ngày này đã hết chỗ. Vui lòng chọn ngày khác!");
            }

            $totalAmount = $this->calculateTotalAmount($tour, $validated);


            $booking = Booking::create([
                'user_id' => auth()->id(),
                'tour_id' => $tour->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'adults' => $validated['adults'],
                'children' => $validated['children'] ?? 0,
                'infants' => $validated['infants'] ?? 0,
                'total_people' => $totalPeople,
                'special_requests' => $validated['special_requests'] ?? null,
                'status' => 'pending',
                'total_amount' => $totalAmount,
            ]);

            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'created_booking',
                'description' => "Đã tạo đặt chỗ #{$booking->booking_code} cho tour {$tourLocked->name} (KH: {$startDate})",
                'properties' => [
                    'booking_id' => $booking->id,
                    'tour_id' => $tour->id,
                    'amount' => $totalAmount,
                    'slots' => $requiredSlots,
                    'start_date' => $startDate,
                ],
            ]);
            
            DB::commit();

            try {
                Mail::to($booking->email)->queue(new BookingConfirmationMail($booking));
            } catch (\Exception $e) {
                // Log error but don't fail the booking
                \Log::error('Failed to send booking confirmation email: ' . $e->getMessage());
            }

            // Redirect to payment
            return redirect()->route('payments.show', $booking)
                ->with('success', 'Đặt tour thành công! Vui lòng hoàn tất thanh toán.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking failed: ' . $e->getMessage());

            $message = $e->getMessage();

            return back()->withInput()->with('error', $message);
        }
    }

    /**
     * Display the specified booking.
     */
    public function show(Booking $booking)
    {
        // Check authorization
        if ($booking->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'Bạn không có quyền truy cập.');
        }

        $booking->load(['tour.destination', 'tour.images', 'payments']);

        // Check if user can write review
        $canReview = false;
        if (auth()->check() && !auth()->user()->isAdmin()) {
            $hasBooking = $booking->isPaid() && $booking->status === 'confirmed' && $booking->tour->end_date < now();
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

        // Load tour
        $booking->load('tour');
        
        // Check if can cancel
        $error = $booking->getCancellationError();
        if ($error) {
            return back()->with('error', $error);
        }

        DB::beginTransaction();

        try {
            // Cancel booking
            $result = $booking->cancel();
            
            if (!$result) {
                return back()->with('error', 'Không thể hủy đặt chỗ này.');
            }

            DB::commit();

            return redirect()->route('bookings.show', $booking)
                ->with('success', 'Đã hủy đặt chỗ thành công.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking cancellation failed: ' . $e->getMessage());
            
            return back()->with('error', 'Có lỗi xảy ra khi hủy đặt chỗ. Vui lòng thử lại.');
        }
    }

    private function checkAvailability(Tour $tour, $date, int $requiredSlots): bool
    {
        return $tour->hasAvailableSlots($date, $requiredSlots);
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
