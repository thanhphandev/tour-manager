<?php

namespace Database\Factories;

use App\Models\TourImage;
use App\Models\Tour;
use App\Models\Destination; // Import Destination
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TourImageFactory extends Factory
{
    protected $model = TourImage::class;

    public function definition(): array
    {
        return [
            // LIÊN KẾT
            'tour_id' => function () {
                return Tour::query()->inRandomOrder()->first()->id ?? Tour::factory();
            },

            // THÔNG TIN ẢNH
            'image_path' => function (array $attributes) {
                $tour = Tour::find($attributes['tour_id']);
                // Ensure tour has destination loaded
                if (!$tour->relationLoaded('destination')) {
                    $tour->load('destination');
                }
                
                $destinationSlug = $tour->destination->slug ?? 'default';
                
                $destinationsData = \Database\Seeders\DestinationData::getDestinations();
                $images = $destinationsData[$destinationSlug]['images'] ?? \Database\Seeders\DestinationData::getDefaultImages();
                
                return fake()->randomElement($images);
            },
            
            // Sử dụng tên Tour làm cơ sở cho Alt Text
            'alt_text' => function (array $attributes) {
                $tour = Tour::find($attributes['tour_id']);
                return Str::limit(($tour->name ?? 'Tour Image') . ' - ' . fake()->randomElement(['Ảnh 1', 'Ảnh chi tiết']), 50);
            },
            
            // THUỘC TÍNH
            'is_primary' => fake()->boolean(20), 
            'order' => fake()->numberBetween(1, 10),
        ];
    }
    
    /**
     * Factory State: Đánh dấu ảnh này là ảnh chính (Primary Image).
     */
    public function primary(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_primary' => true,
            'order' => 1,
        ]);
    }
}