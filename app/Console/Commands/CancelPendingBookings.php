<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use Carbon\Carbon;

class CancelPendingBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:cancel-pending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel all bookings with pending status to free up slots';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $pendingBookings = Booking::where('status', 'pending')->get();
        $count = 0;
        foreach ($pendingBookings as $booking) {
            $booking->update(['status' => 'cancelled']);
            $count++;
        }
        $this->info("Đã hủy $count booking trạng thái chờ xác nhận.");
    }
}
