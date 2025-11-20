<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Review;
use App\Mail\ReviewRequestMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendReviewRequests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tour:send-review-requests {--days=2 : Days after tour completion}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send review request emails to customers who completed their tours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        $targetDate = Carbon::now()->subDays($days)->format('Y-m-d');
        
        $this->info("Đang tìm các tour kết thúc vào ngày {$targetDate}...");

        // Lấy bookings đã hoàn thành, chưa có review
        $bookings = Booking::with(['tour', 'user'])
            ->where('status', 'confirmed')
            ->whereDoesntHave('review')
            ->whereHas('tour', function($query) use ($targetDate) {
                $query->whereDate('end_date', $targetDate);
            })
            ->get();

        if ($bookings->isEmpty()) {
            $this->info('Không có booking nào cần gửi yêu cầu review.');
            return 0;
        }

        $sent = 0;
        $failed = 0;

        foreach ($bookings as $booking) {
            try {
                Mail::to($booking->user->email)
                    ->queue(new ReviewRequestMail($booking));
                
                $this->info("✓ Đã gửi email yêu cầu review đến {$booking->user->email} cho tour \"{$booking->tour->name}\"");
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
