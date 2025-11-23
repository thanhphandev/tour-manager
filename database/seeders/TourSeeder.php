<?php

namespace Database\Seeders;

use Database\Factories\TourFactory;
use Illuminate\Database\Seeder;
use App\Models\Tour;
use App\Models\Destination;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class TourSeeder extends Seeder
{

    public function run()
    {
        $destinations = Destination::all();

        if ($destinations->isEmpty()) {
            $this->command->warn('⚠️ No destinations found. Please run DestinationSeeder first.');
            return;
        }

        foreach ($destinations as $destination) {
            // Create 3-5 tours for each destination
            Tour::factory(rand(3, 5))->create([
                'destination_id' => $destination->id
            ]);
        }

        $this->command->info('✅ TourSeeder: ' . Tour::count() . ' tours created successfully.');
    }
}
