<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tour;
use App\Models\TourImage;
use Illuminate\Support\Str;

class TourImageSeeder extends Seeder
{
    public function run()
    {
        $tours = Tour::all();

        if ($tours->isEmpty()) {
            // If no tours, warn user
            return;
        }

        foreach ($tours as $tour) {
            // Create 3-5 random images
            TourImage::factory(rand(3, 5))->create([
                'tour_id' => $tour->id
            ]);

            // Ensure one primary image
            TourImage::factory()->primary()->create([
                'tour_id' => $tour->id
            ]);
        }
    }
}
