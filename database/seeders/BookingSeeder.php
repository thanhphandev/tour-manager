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
            
            Booking::factory()->create([
                'user_id' => $users->random()->id,
                'tour_id' => $tours->random()->id,
                'status' => $status
            ]);
        }
        
        $this->command->info('✅ BookingSeeder: 50 bookings created.');
    }
}