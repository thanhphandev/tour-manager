<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\Tour;
use App\Models\User;

class BookingSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $tours = Tour::all();

        if ($users->isEmpty() || $tours->isEmpty()) {
            $this->command->info('❌ BookingSeeder: No users or tours found.');
            return;
        }

        for ($i = 0; $i < 50; $i++) {
            $status = fake()->randomElement(['pending', 'confirmed', 'cancelled']);
            
            $booking = Booking::factory()->create([
                'user_id' => $users->random()->id,
                'tour_id' => $tours->random()->id,
                'status' => $status
            ]);

            // Create Payment based on booking status
            if ($status === 'confirmed') {
                \App\Models\Payment::factory()->success()->create([
                    'booking_id' => $booking->id,
                    'amount' => $booking->total_amount,
                ]);
            } elseif ($status === 'pending') {
                \App\Models\Payment::factory()->create([
                    'booking_id' => $booking->id,
                    'amount' => $booking->total_amount,
                    'status' => 'pending',
                ]);
            } elseif ($status === 'cancelled') {
                // Randomly decide if it was refunded or just cancelled without payment
                if (fake()->boolean(50)) {
                    \App\Models\Payment::factory()->refunded()->create([
                        'booking_id' => $booking->id,
                        'amount' => $booking->total_amount,
                    ]);
                } else {
                     \App\Models\Payment::factory()->failed()->create([
                        'booking_id' => $booking->id,
                        'amount' => $booking->total_amount,
                    ]);
                }
            }
        }
        
        $this->command->info('✅ BookingSeeder: 50 bookings created.');
    }
}