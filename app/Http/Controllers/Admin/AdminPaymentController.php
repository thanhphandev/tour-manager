<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminPaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['booking.tour', 'booking.user']);

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by payment method
        if ($request->has('payment_method') && $request->payment_method) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search by payment code or transaction ID
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('payment_code', 'like', "%{$search}%")
                  ->orWhere('transaction_id', 'like', "%{$search}%");
            });
        }

        $payments = $query->latest()->paginate(20)->withQueryString();

        // Statistics
        $stats = [
            'total_amount' => Payment::where('status', 'success')->sum('amount'),
            'total_payments' => Payment::count(),
            'success' => Payment::where('status', 'success')->count(),
            'pending' => Payment::where('status', 'pending')->count(),
            'failed' => Payment::where('status', 'failed')->count(),
            'refunded' => Payment::where('status', 'refunded')->count(),
        ];

        return view('admin.payments.index', compact('payments', 'stats'));
    }

    public function show(Payment $payment)
    {
        $payment->load(['booking.tour.destination', 'booking.user']);
        return view('admin.payments.show', compact('payment'));
    }

    public function refund(Request $request, Payment $payment)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        if ($payment->status !== 'success') {
            return back()->with('error', 'Chỉ có thể hoàn tiền cho thanh toán đã thành công.');
        }

        DB::beginTransaction();
        try {
            // Update payment status
            $payment->update([
                'status' => 'refunded',
                'notes' => $request->reason,
            ]);

            // Update booking status to cancelled
            $payment->booking->update([
                'status' => 'cancelled',
            ]);

            // Restore max_people slots when refunding
            $booking = $payment->booking;
            if ($booking->tour->max_people !== null) {
                $booking->tour->increment('max_people', $booking->total_people);
            }

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'refunded_payment',
                'description' => "Đã hoàn tiền thanh toán #{$payment->payment_code} - {$request->reason}",
                'properties' => [
                    'payment_id' => $payment->id,
                    'payment_code' => $payment->payment_code,
                    'booking_id' => $payment->booking_id,
                    'booking_code' => $payment->booking->booking_code,
                    'amount' => $payment->amount,
                    'reason' => $request->reason,
                    'restored_slots' => $booking->total_people,
                ],
            ]);

            DB::commit();
            return redirect()->route('admin.payments.show', $payment)
                ->with('success', 'Đã hoàn tiền thành công.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Payment refund failed: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra khi hoàn tiền. Vui lòng thử lại.');
        }
    }

    /**
     * Mark payment as success manually
     */
    public function markAsSuccess(Request $request, Payment $payment)
    {
        if ($payment->status === 'success') {
            return back()->with('error', 'Thanh toán này đã được xác nhận thành công rồi.');
        }

        DB::beginTransaction();
        try {
            $payment->markAsSuccess(
                $request->transaction_id ?? 'MANUAL-' . now()->timestamp,
                ['manual_confirmation' => true, 'confirmed_by' => auth()->user()->name]
            );

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'confirmed_payment',
                'description' => "Đã xác nhận thanh toán #{$payment->payment_code}",
                'properties' => [
                    'payment_id' => $payment->id,
                    'payment_code' => $payment->payment_code,
                    'booking_id' => $payment->booking_id,
                    'booking_code' => $payment->booking->booking_code,
                    'amount' => $payment->amount,
                ],
            ]);

            DB::commit();
            return redirect()->route('admin.payments.show', $payment)
                ->with('success', 'Đã xác nhận thanh toán thành công.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Payment confirmation failed: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra khi xác nhận thanh toán. Vui lòng thử lại.');
        }
    }

    /**
     * Mark payment as failed manually
     */
    public function markAsFailed(Request $request, Payment $payment)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        if ($payment->status === 'success') {
            return back()->with('error', 'Không thể đánh dấu thất bại cho thanh toán đã thành công.');
        }

        DB::beginTransaction();
        try {
            $payment->markAsFailed($request->reason);

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'failed_payment',
                'description' => "Đã đánh dấu thanh toán #{$payment->payment_code} là thất bại - {$request->reason}",
                'properties' => [
                    'payment_id' => $payment->id,
                    'payment_code' => $payment->payment_code,
                    'booking_id' => $payment->booking_id,
                    'reason' => $request->reason,
                ],
            ]);

            DB::commit();
            return redirect()->route('admin.payments.show', $payment)
                ->with('success', 'Đã đánh dấu thanh toán thất bại.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Payment mark as failed: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra. Vui lòng thử lại.');
        }
    }
}
