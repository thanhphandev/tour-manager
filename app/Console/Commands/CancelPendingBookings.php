<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingCancellationMail;

class CancelPendingBookings extends Command
{
    protected $signature = 'bookings:cancel-pending';
    protected $description = 'Hủy các booking ở trạng thái Pending quá 24 giờ';

    public function handle()
    {
        // 1. Tìm các booking pending quá 24h
        $expiredBookings = Booking::where('status', 'pending')
            ->where('created_at', '<', now()->subHours(24))
            ->get();

        $count = 0;

        foreach ($expiredBookings as $booking) {
            $booking->update(['status' => 'cancelled']);
            
            Mail::to($booking->email)->queue(new BookingCancellationMail($booking, 'Đã quá hạn thanh toán 24h', null));
            
            $count++;
        }

        $this->info("Đã hủy tự động {$count} booking quá hạn thanh toán.");
    }
}