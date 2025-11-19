<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\PaymentConfirmationMail;
use App\Mail\BookingCancellationMail;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    public function index()
    {
        $query = Booking::with(['user', 'tour.destination']);

        // Search
        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if (request('status')) {
            $query->where('status', request('status'));
        }

        // Filter by date
        if (request('date')) {
            $query->whereDate('created_at', request('date'));
        }

        // Get statistics
        $stats = [
            'total' => Booking::count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
        ];

        $bookings = $query->latest()->paginate(10)->withQueryString();
        
        return view('admin.bookings.index', compact('bookings', 'stats'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user.bookings', 'tour.destination', 'payments']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function confirm(Booking $booking)
    {
        if ($booking->status === 'confirmed') {
            return redirect()->route('admin.bookings.show', $booking)
                ->with('info', 'Đặt chỗ này đã được xác nhận trước đó.');
        }

        DB::beginTransaction();
        try {
            // Update booking status
            $booking->update(['status' => 'confirmed']);

            // Check if payment already exists
            $existingPayment = $booking->payments()
                ->where('status', 'success')
                ->first();

            if (!$existingPayment) {
                // Create payment with cash method
                $payment = Payment::create([
                    'booking_id' => $booking->id,
                    'payment_code' => Payment::generatePaymentCode(),
                    'payment_method' => 'cash',
                    'amount' => $booking->total_amount,
                    'status' => 'success',
                    'transaction_id' => 'CASH-' . now()->timestamp,
                    'transaction_data' => [
                        'method' => 'cash',
                        'confirmed_by' => auth()->user()->name,
                        'confirmed_at' => now()->toDateTimeString(),
                        'note' => 'Thanh toán bằng tiền mặt - Xác nhận bởi admin'
                    ],
                    'paid_at' => now(),
                    'notes' => 'Thanh toán bằng tiền mặt tại văn phòng',
                ]);

                // Log activity
                ActivityLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'confirmed_booking_with_payment',
                    'description' => "Đã xác nhận đặt chỗ #{$booking->booking_code} và tạo thanh toán tiền mặt #{$payment->payment_code}",
                    'properties' => [
                        'booking_id' => $booking->id,
                        'booking_code' => $booking->booking_code,
                        'payment_id' => $payment->id,
                        'payment_code' => $payment->payment_code,
                        'amount' => $booking->total_amount,
                        'payment_method' => 'cash',
                    ],
                ]);
            } else {
                // Log activity for confirmation only
                ActivityLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'confirmed_booking',
                    'description' => "Đã xác nhận đặt chỗ #{$booking->booking_code}",
                    'properties' => [
                        'booking_id' => $booking->id,
                        'booking_code' => $booking->booking_code,
                    ],
                ]);
            }

            DB::commit();

            if (!$existingPayment) {
                try {
                    Mail::to($booking->email)->queue(new PaymentConfirmationMail($payment));
                } catch (\Exception $e) {
                    Log::error('Failed to send payment confirmation email: ' . $e->getMessage());
                }
            }

            return redirect()->route('admin.bookings.show', $booking)
                ->with('success', 'Đặt chỗ đã được xác nhận thành công.' . 
                    (!$existingPayment ? ' Đã tạo thanh toán tiền mặt và gửi email xác nhận.' : ''));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking confirmation failed: ' . $e->getMessage());
            
            return redirect()->route('admin.bookings.show', $booking)
                ->with('error', 'Có lỗi xảy ra khi xác nhận đặt chỗ. Vui lòng thử lại.');
        }
    }

    public function cancel(Booking $booking)
    {
        if($booking->getSuccessfulPayment() !== null){
            return redirect()->route('admin.bookings.show', $booking)
                ->with('error', 'Không thể hủy đặt chỗ đã được thanh toán ngay! vui lòng tìm kiếm mã thanh toán để hủy.');
        }
        $booking->update(['status' => 'cancelled']);

        return redirect()->route('admin.bookings.show', $booking)
            ->with('success', 'Đặt chỗ đã được hủy thành công.');
    }
}
