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
            $this->command->warn('⚠️ No tours found. Please run TourSeeder first.');
            return;
        }

        foreach ($tours as $tour) {
            $lowerName = Str::lower($tour->name);

            // Define image sets based on destination keywords
            if (Str::contains($lowerName, 'hạ long')) {
                $images = [
                    ['url' => 'https://images.unsplash.com/photo-1549887534-3db1bd59dcca', 'alt' => 'Ha Long Bay view', 'primary' => true],
                    ['url' => 'https://images.unsplash.com/photo-1586162548442-1b4b2b2f94a1', 'alt' => 'Cruise ship in Ha Long', 'primary' => false],
                    ['url' => 'https://images.unsplash.com/photo-1570232995323-9e1e3c338aa8', 'alt' => 'Kayaking in Ha Long Bay', 'primary' => false],
                ];
            } elseif (Str::contains($lowerName, 'đà lạt')) {
                $images = [
                    ['url' => 'https://images.unsplash.com/photo-1588944358444-44cf8d4b3b77', 'alt' => 'Da Lat Flower Garden', 'primary' => true],
                    ['url' => 'https://images.unsplash.com/photo-1601905390274-9b1b05b9ec7e', 'alt' => 'Xuan Huong Lake', 'primary' => false],
                    ['url' => 'https://images.unsplash.com/photo-1606799078610-5d377cdcd8e8', 'alt' => 'Da Lat city view', 'primary' => false],
                ];
            } elseif (Str::contains($lowerName, 'hội an')) {
                $images = [
                    ['url' => 'https://images.unsplash.com/photo-1585032659293-2f33e4c1a6e0', 'alt' => 'Hoi An lantern street', 'primary' => true],
                    ['url' => 'https://images.unsplash.com/photo-1572232282278-5a5b3e0e0409', 'alt' => 'Ancient town by river', 'primary' => false],
                    ['url' => 'https://images.unsplash.com/photo-1582582425721-4ecf8c9b2ee5', 'alt' => 'Night market in Hoi An', 'primary' => false],
                ];
            } else {
                // Fallback random travel-related images
                $images = [
                    ['url' => 'https://source.unsplash.com/featured/?travel', 'alt' => 'Beautiful travel scene', 'primary' => true],
                    ['url' => 'https://source.unsplash.com/featured/?landscape', 'alt' => 'Scenic view', 'primary' => false],
                    ['url' => 'https://source.unsplash.com/featured/?adventure', 'alt' => 'Adventure activity', 'primary' => false],
                ];
            }

            foreach ($images as $index => $image) {
                TourImage::create([
                    'tour_id' => $tour->id,
                    'image_path' => $image['url'],
                    'alt_text' => $image['alt'],
                    'is_primary' => $image['primary'],
                    'order' => $index + 1,
                ]);
            }
        }

        $this->command->info('✅ TourImageSeeder: ' . TourImage::count() . ' images have been created successfully.');
    }
}
