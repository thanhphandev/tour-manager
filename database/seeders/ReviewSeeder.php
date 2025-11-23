<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\Booking;

class ReviewSeeder extends Seeder
{
    public function run()
    {
        // Get completed bookings that don't have reviews yet (optional check, but good for re-seeding)
        // For simplicity, just get all completed bookings
        $bookings = Booking::where('status', 'confirmed')->get();

        foreach ($bookings as $booking) {
            // 80% chance to leave a review
            if (fake()->boolean(80)) {
                try {
                    Review::factory()->create([
                        'user_id'    => $booking->user_id,
                        'tour_id'    => $booking->tour_id,
                        'booking_id' => $booking->id,
                    ]);
                } catch (\Exception $e) {
                    $this->command->error('Error creating review for booking ' . $booking->id . ': ' . $e->getMessage());
                }
            }
        }
        
        $this->command->info('✅ ReviewSeeder: Reviews created for completed bookings.');
    }
}
