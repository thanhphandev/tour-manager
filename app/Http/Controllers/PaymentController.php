<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Mail\PaymentConfirmationMail;
use App\Services\VNPayService;
use App\Services\PayPalService;
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
     * Process VNPay payment
     */
    public function processVNPay(Request $request, Booking $booking)
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

        try {
            // Load tour relationship
            $booking->load('tour');

            // Create payment record
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'payment_code' => Payment::generatePaymentCode(),
                'payment_method' => 'vnpay',
                'amount' => $booking->total_amount,
                'status' => 'pending',
            ]);

            // Prepare VNPay data
            $vnpayService = new VNPayService();
            $paymentUrl = $vnpayService->createPaymentUrl([
                'amount' => $booking->total_amount,
                'txn_ref' => $payment->payment_code,
                'order_info' => 'Thanh toan tour: ' . $booking->tour->name,
                'order_type' => 'billpayment',
                'locale' => 'vn',
                'ip_addr' => $request->ip(),
                'bill_email' => $booking->email,
                'bill_mobile' => $booking->phone,
                'bill_firstname' => explode(' ', $booking->customer_name)[0] ?? '',
                'bill_lastname' => substr($booking->customer_name, strpos($booking->customer_name, ' ') + 1) ?: '',
            ]);

            // Redirect to VNPay
            return redirect($paymentUrl);

        } catch (\Exception $e) {
            Log::error('VNPay payment initialization failed: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi khởi tạo thanh toán VNPay. Vui lòng thử lại.');
        }
    }

    /**
     * Process PayPal payment
     */
    public function processPayPal(Request $request, Booking $booking)
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

        try {
            DB::beginTransaction();

            // Load tour relationship
            $booking->load('tour');

            // Create payment record
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'payment_code' => Payment::generatePaymentCode(),
                'payment_method' => 'paypal',
                'amount' => $booking->total_amount,
                'status' => 'pending',
            ]);

            // Create PayPal order
            $paypalService = new PayPalService();
            $result = $paypalService->createOrder([
                'amount' => $booking->total_amount,
                'description' => 'Tour: ' . $booking->tour->name,
                'invoice_id' => $payment->payment_code,
                'custom_id' => $booking->booking_code,
                'customer_name' => $booking->customer_name,
                'customer_email' => $booking->email,
            ]);

            if (!$result['success']) {
                // Mark payment as failed
                $payment->markAsFailed('PayPal order creation failed: ' . $result['error']);
                
                DB::rollBack();
                
                Log::error('PayPal order creation failed', [
                    'booking_id' => $booking->id,
                    'error' => $result['error'],
                ]);

                return redirect()->route('payments.error', $booking)
                    ->with([
                        'errorMessage' => 'Không thể khởi tạo thanh toán PayPal: ' . $result['error'],
                        'errorCode' => 'PAYPAL_CREATE_FAILED',
                        'paymentMethod' => 'PayPal',
                    ]);
            }

            // Save PayPal order ID to payment
            $payment->update([
                'transaction_data' => [
                    'paypal_order_id' => $result['order_id'],
                    'created_at' => now()->toDateTimeString(),
                ],
            ]);

            DB::commit();

            // Redirect to PayPal approval URL
            return redirect($result['approval_url']);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('PayPal payment initialization failed', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('payments.error', $booking)
                ->with([
                    'errorMessage' => 'Có lỗi xảy ra khi khởi tạo thanh toán. Vui lòng thử lại.',
                    'errorCode' => 'SYSTEM_ERROR',
                    'paymentMethod' => 'PayPal',
                ]);
        }
    }

    /**
     * PayPal callback handler (success)
     */
    public function paypalCallback(Request $request)
    {
        try {
            $token = $request->query('token'); // PayPal Order ID
            $payerId = $request->query('PayerID');

            if (!$token) {
                Log::error('PayPal callback missing token');
                
                return redirect()->route('bookings.history')
                    ->with('error', 'Không tìm thấy thông tin thanh toán PayPal.');
            }

            // Find payment by PayPal order ID
            $payment = Payment::whereJsonContains('transaction_data->paypal_order_id', $token)
                ->where('status', 'pending')
                ->first();

            if (!$payment) {
                Log::error('Payment not found for PayPal order', ['order_id' => $token]);
                
                return redirect()->route('bookings.history')
                    ->with('error', 'Không tìm thấy thông tin thanh toán.');
            }

            // Check if already processed
            if ($payment->status === 'success') {
                return redirect()->route('bookings.success', $payment->booking)
                    ->with('info', 'Đơn đặt tour này đã được thanh toán.');
            }

            DB::beginTransaction();

            // Load booking and tour
            $payment->load(['booking.tour']);
            $booking = $payment->booking;

            // Capture PayPal order
            $paypalService = new PayPalService();
            $result = $paypalService->captureOrder($token);

            if (!$result['success']) {
                // Mark payment as failed
                $payment->markAsFailed('PayPal capture failed: ' . $result['error']);
                
                DB::rollBack();
                
                Log::error('PayPal capture failed', [
                    'order_id' => $token,
                    'error' => $result['error'],
                ]);

                return redirect()->route('payments.error', $booking)
                    ->with([
                        'errorMessage' => 'Thanh toán PayPal thất bại: ' . $result['error'],
                        'errorCode' => 'PAYPAL_CAPTURE_FAILED',
                        'transactionId' => $token,
                        'paymentMethod' => 'PayPal',
                    ]);
            }

            // Update payment with transaction data
            $transactionData = array_merge(
                $payment->transaction_data ?? [],
                $result['data'],
                ['payer_id' => $payerId]
            );

            // Mark as success
            $payment->markAsSuccess($result['transaction_id'], $transactionData);

            // Send confirmation email
            try {
                Mail::to($booking->email)->send(new PaymentConfirmationMail($payment));
            } catch (\Exception $e) {
                Log::error('Failed to send payment confirmation email', [
                    'error' => $e->getMessage(),
                ]);
            }

            DB::commit();

            return redirect()->route('bookings.success', $booking)
                ->with('success', 'Thanh toán PayPal thành công! Đơn đặt tour của bạn đã được xác nhận.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('PayPal callback processing failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('bookings.history')
                ->with('error', 'Có lỗi xảy ra khi xử lý thanh toán. Vui lòng liên hệ bộ phận hỗ trợ.');
        }
    }

    /**
     * PayPal cancel handler
     */
    public function paypalCancel(Request $request)
    {
        $token = $request->query('token');

        Log::info('PayPal payment cancelled by user', ['order_id' => $token]);

        // Find payment
        $payment = Payment::whereJsonContains('transaction_data->paypal_order_id', $token)
            ->where('status', 'pending')
            ->first();

        if ($payment) {
            $booking = $payment->booking;
            
            return redirect()->route('payments.error', $booking)
                ->with([
                    'errorMessage' => 'Bạn đã hủy thanh toán PayPal.',
                    'errorCode' => 'USER_CANCELLED',
                    'transactionId' => $token,
                    'paymentMethod' => 'PayPal',
                ]);
        }

        return redirect()->route('bookings.history')
            ->with('info', 'Thanh toán đã bị hủy.');
    }

    /**
     * Payment error page
     */
    public function error(Booking $booking)
    {
        // Check if user owns this booking
        if ($booking->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $booking->load('tour');

        return view('payments.error', [
            'booking' => $booking,
            'errorMessage' => session('errorMessage', 'Giao dịch không thành công'),
            'errorCode' => session('errorCode'),
            'transactionId' => session('transactionId'),
            'paymentMethod' => session('paymentMethod'),
            'failedAt' => now()->format('d/m/Y H:i:s'),
        ]);
    }

    /**
     * VNPay callback handler
     */
    public function vnpayCallback(Request $request)
    {
        try {
            $vnpayService = new VNPayService();
            $result = $vnpayService->validateCallback($request->all());

            if (!$result['success']) {
                Log::error('VNPay callback validation failed: ' . $result['message']);
                
                // Find payment by txn_ref if available
                $payment = null;
                if (isset($request->vnp_TxnRef)) {
                    $payment = Payment::where('payment_code', $request->vnp_TxnRef)->first();
                }
                
                // Mark payment as failed
                if ($payment) {
                    $payment->markAsFailed('VNPay validation failed: ' . $result['message'] . ' (Code: ' . ($result['data']['response_code'] ?? 'N/A') . ')');
                }
                
                if ($payment && $payment->booking) {
                    return redirect()->route('payments.error', $payment->booking)
                        ->with([
                            'errorMessage' => 'Thanh toán thất bại: ' . $result['message'],
                            'errorCode' => $result['data']['response_code'] ?? 'VNPAY_ERROR',
                            'transactionId' => $request->vnp_TxnRef ?? null,
                            'paymentMethod' => 'VNPay',
                        ]);
                }
                
                return redirect()->route('bookings.history')
                    ->with('error', 'Thanh toán thất bại: ' . $result['message']);
            }

            // Find payment by payment_code (txn_ref)
            $payment = Payment::where('payment_code', $result['data']['txn_ref'])->first();

            if (!$payment) {
                Log::error('Payment not found for txn_ref: ' . $result['data']['txn_ref']);
                
                return redirect()->route('bookings.history')
                    ->with('error', 'Không tìm thấy thông tin thanh toán.');
            }

            // Check if already processed
            if ($payment->status === 'success') {
                return redirect()->route('bookings.success', $payment->booking)
                    ->with('info', 'Đơn đặt tour này đã được thanh toán.');
            }

            DB::beginTransaction();

            // Load booking and tour
            $payment->load(['booking.tour']);
            $booking = $payment->booking;

            // Prepare transaction data
            $transactionData = [
                'bank_code' => $result['data']['bank_code'],
                'bank_tran_no' => $result['data']['bank_tran_no'],
                'card_type' => $result['data']['card_type'],
                'pay_date' => $result['data']['pay_date'],
                'order_info' => $result['data']['order_info'],
            ];

            // Mark as success
            $payment->markAsSuccess($result['data']['transaction_id'], $transactionData);

            // Send confirmation email
            try {
                Mail::to($booking->email)->send(new PaymentConfirmationMail($payment));
            } catch (\Exception $e) {
                Log::error('Failed to send payment confirmation email: ' . $e->getMessage());
            }

            DB::commit();

            return redirect()->route('bookings.success', $booking)
                ->with('success', 'Thanh toán VNPay thành công! Đơn đặt tour của bạn đã được xác nhận.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('VNPay callback processing failed: ' . $e->getMessage());

            return redirect()->route('bookings.history')
                ->with('error', 'Có lỗi xảy ra khi xử lý thanh toán. Vui lòng liên hệ bộ phận hỗ trợ.');
        }
    }
}
