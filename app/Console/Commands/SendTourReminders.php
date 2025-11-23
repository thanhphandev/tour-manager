<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Mail\TourReminderMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendTourReminders extends Command
{
    protected $signature = 'tour:send-reminders {--days=3 : Days before departure}';
    protected $description = 'Send tour reminder emails to customers whose tours are departing soon';

    public function handle()
    {
        $days = (int) $this->option('days');

        if ($days < 0) {
            $this->error("The --days option cannot be negative.");
            return 1;
        }

        $targetDate = Carbon::now()->addDays($days)->toDateString();

        $this->info("Finding tours that start on {$targetDate}...");

        $bookings = Booking::with(['tour', 'user'])
            ->where('status', 'confirmed')
            ->whereDate('start_date', $targetDate)
            ->whereNull('reminded_at')   // prevent duplicate reminders
            ->get();

        if ($bookings->isEmpty()) {
            $this->info('No tours require reminders.');
            return 0;
        }

        $sent = 0;
        $failed = 0;

        foreach ($bookings as $booking) {

            // Skip if no email available
            if (!$booking->email) {
                $this->warn("Skipping booking ID {$booking->id} — no email found.");
                $failed++;
                continue;
            }

            // Skip if relationship is missing (data corruption)
            if (!$booking->tour) {
                $this->warn("Skipping booking ID {$booking->id} — missing related tour.");
                $failed++;
                continue;
            }

            try {
                Mail::to($booking->email)->queue(new TourReminderMail($booking));

                // Mark as sent to avoid sending again tomorrow
                $booking->update(['reminded_at' => now()]);

                $this->info("✓ Reminded {$booking->email} for tour \"{$booking->tour->name}\"");
                $sent++;

            } catch (\Throwable $e) {
                $this->error("✗ Failed to remind {$booking->email}: {$e->getMessage()}");

                // Log full error for debugging
                Log::error('Tour reminder email failure', [
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
