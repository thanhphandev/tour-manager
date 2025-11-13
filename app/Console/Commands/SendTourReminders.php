<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Mail\TourReminderMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendTourReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tour:send-reminders {--days=3 : Days before departure}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send tour reminder emails to customers whose tours are departing soon';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        $targetDate = Carbon::now()->addDays($days)->format('Y-m-d');
        
        $this->info("Đang tìm các tour khởi hành vào ngày {$targetDate}...");

        $bookings = Booking::with(['tour', 'user'])
            ->where('status', 'confirmed')
            ->whereHas('tour', function($query) use ($targetDate) {
                $query->whereDate('start_date', $targetDate);
            })
            ->get();

        if ($bookings->isEmpty()) {
            $this->info('Không có tour nào cần gửi nhắc nhở.');
            return 0;
        }

        $sent = 0;
        $failed = 0;

        foreach ($bookings as $booking) {
            try {
                Mail::to($booking->user->email)
                    ->send(new TourReminderMail($booking));
                
                $this->info("✓ Đã gửi email nhắc nhở đến {$booking->user->email} cho tour \"{$booking->tour->name}\"");
                $sent++;
            } catch (\Exception $e) {
                $this->error("✗ Lỗi khi gửi email đến {$booking->user->email}: {$e->getMessage()}");
                $failed++;
            }
        }

        $this->info("\n=== Tổng kết ===");
        $this->info("Đã gửi thành công: {$sent}");
        if ($failed > 0) {
            $this->warn("Thất bại: {$failed}");
        }

        return 0;
    }
}
