<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Mail\ReviewRequestMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SendReviewRequests extends Command
{
    protected $signature = 'tour:send-review-requests {--days=2 : Days after tour completion}';
    protected $description = 'Send review request emails to customers who completed their tours';

    public function handle()
    {
        $days = (int) $this->option('days');

        if ($days < 0) {
            $this->error("The --days option cannot be negative.");
            return 1;
        }

        $targetDate = Carbon::now()->subDays($days)->toDateString();

        $this->info("Finding tours that ended on {$targetDate}...");

        $bookings = Booking::with(['tour'])
            ->where('status', 'confirmed')
            ->whereDate('end_date', $targetDate)
            ->whereDoesntHave('review')
            ->whereNull('review_request_sent_at')   // make sure only sent once
            ->get();

        if ($bookings->isEmpty()) {
            $this->info('No bookings require review requests.');
            return 0;
        }

        $sent = 0;
        $failed = 0;

        foreach ($bookings as $booking) {

            // Skip missing email
            if (!$booking->email) {
                $this->warn("Skipping booking ID {$booking->id} — no email.");
                $failed++;
                continue;
            }

            // Skip missing tour (data corruption)
            if (!$booking->tour) {
                $this->warn("Skipping booking ID {$booking->id} — missing related tour.");
                $failed++;
                continue;
            }

            try {
                Mail::to($booking->email)->queue(new ReviewRequestMail($booking));

                // Mark as sent to avoid duplicates
                $booking->update(['review_request_sent_at' => now()]);

                $this->info("✓ Sent review request to {$booking->email} for tour \"{$booking->tour->name}\"");
                $sent++;

            } catch (\Throwable $e) {
                $this->error("✗ Failed to send to {$booking->email}: {$e->getMessage()}");

                Log::error('Review request email failed', [
                    'booking_id' => $booking->id,
                    'email' => $booking->email,
                    'error' => $e->getMessage(),
                ]);

                $failed++;
            }
        }

        $this->info("\n=== Summary ===");
        $this->info("Successfully sent: {$sent}");
        if ($failed > 0) {
            $this->warn("Failed: {$failed}");
        }

        return 0;
    }
}
