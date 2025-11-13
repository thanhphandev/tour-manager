<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Mail\PaymentConfirmationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    /**
     * Show payment page
     */
    public function show(Booking $booking)
    {
        // Check if user owns this booking
        if ($booking->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Check if already paid
        $successfulPayment = $booking->payments()->where('status', 'success')->first();
        if ($successfulPayment) {
            return redirect()->route('bookings.success', $booking)
                ->with('info', 'Đơn đặt tour này đã được thanh toán.');
        }

        $booking->load('tour');

        return view('payments.show', compact('booking'));
    }

    /**
     * Process mock payment
     */
    public function processMock(Request $request, Booking $booking)
    {
        // Check if user owns this booking
        if ($booking->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Check if already paid
        $successfulPayment = $booking->payments()->where('status', 'success')->first();
        if ($successfulPayment) {
            return redirect()->route('bookings.success', $booking)
                ->with('info', 'Đơn đặt tour này đã được thanh toán.');
        }

        $request->validate([
            'card_number' => 'required|string|min:16|max:19',
            'card_name' => 'required|string|max:255',
            'expiry_date' => 'required|string|size:5', // MM/YY
            'cvv' => 'required|string|size:3',
        ], [
            'card_number.required' => 'Vui lòng nhập số thẻ',
            'card_number.min' => 'Số thẻ phải có ít nhất 16 chữ số',
            'card_name.required' => 'Vui lòng nhập tên trên thẻ',
            'expiry_date.required' => 'Vui lòng nhập ngày hết hạn',
            'expiry_date.size' => 'Ngày hết hạn phải có định dạng MM/YY',
            'cvv.required' => 'Vui lòng nhập mã CVV',
            'cvv.size' => 'Mã CVV phải có 3 chữ số',
        ]);

        try {
            DB::beginTransaction();

            // Load tour relationship for email
            $booking->load('tour');

            // Create payment record
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'payment_code' => Payment::generatePaymentCode(),
                'payment_method' => 'mock',
                'amount' => $booking->total_amount,
                'status' => 'pending',
            ]);

            // Simulate payment processing (always success for mock)
            sleep(2); // Simulate API call

            // Mock transaction data
            $transactionData = [
                'card_last4' => substr(str_replace(' ', '', $request->card_number), -4),
                'card_name' => $request->card_name,
                'processed_at' => now()->toDateTimeString(),
                'bank' => 'Mock Bank',
            ];

            $transactionId = 'MOCK-' . strtoupper(uniqid());

            // Mark as success (this also updates booking payment_id and status)
            $payment->markAsSuccess($transactionId, $transactionData);

            // Reload booking to get updated payment_id
            $booking->refresh();

            // Gửi email xác nhận thanh toán
            try {
                Mail::to($booking->email)->send(new PaymentConfirmationMail($payment));
            } catch (\Exception $e) {
                Log::error('Failed to send payment confirmation email: ' . $e->getMessage());
            }

            DB::commit();

            return redirect()->route('bookings.success', $booking)
                ->with('success', 'Thanh toán thành công! Đơn đặt tour của bạn đã được xác nhận.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment failed: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi xử lý thanh toán. Vui lòng thử lại.');
        }
    }

    /**
     * Process VNPay payment (placeholder for future)
     */
    public function processVNPay(Booking $booking)
    {
        // TODO: Implement VNPay integration
        return redirect()->back()
            ->with('info', 'VNPay integration đang được phát triển. Vui lòng sử dụng Mock Payment.');
    }

    /**
     * VNPay callback (placeholder for future)
     */
    public function vnpayCallback(Request $request)
    {
        // TODO: Handle VNPay callback
    }
}
