<?php

namespace Database\Factories;

use App\Models\Tour;
use App\Models\Destination;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TourFactory extends Factory
{
    protected $model = Tour::class;

    public function definition(): array
    {
        return [
            // LIÊN KẾT
            'destination_id' => function () {
                return Destination::query()->inRandomOrder()->first()->id ?? Destination::factory();
            },

            // THÔNG TIN CƠ BẢN
            'name' => function (array $attributes) {
                $destination = Destination::find($attributes['destination_id']);
                $durationDays = fake()->numberBetween(2, 7);
                $tourNameTemplate = fake()->randomElement([
                    'Hành trình Khám phá', 'Nghỉ dưỡng Cao cấp', 'Tour Tiết kiệm', 'Phiêu lưu Mạo hiểm', 'Trải nghiệm Văn hóa', 'Chuyến đi Gia đình', 'Tour Lãng mạn'
                ]);
                return $tourNameTemplate . ' ' . ($destination->name ?? 'Vietnam') . ' ' . $durationDays . ' Ngày' . ' ' . ($durationDays - 1) . ' Đêm';
            },
            'slug' => function (array $attributes) {
                $uniqueSlugSuffix = Str::random(6);
                return Str::slug($attributes['name']) . '-' . $uniqueSlugSuffix;
            },
            'short_description' => fake()->sentence(15),
            'full_description' => fake()->paragraphs(4, true),
            'itinerary' => fake()->paragraphs(6, true), // Lịch trình chi tiết giả
            
            // GIÁ
            'price_adult' => function () {
                $priceMultiplier = fake()->numberBetween(30, 200);
                return $priceMultiplier * 50000;
            },
            'price_child' => function (array $attributes) {
                $fraction = fake()->randomFloat(2, 0.5, 0.75); // 50%–75% of adult
                $price = $attributes['price_adult'] * $fraction;
                
                // Round to nearest 50,000
                return round($price / 50000) * 50000;
            },
            'price_infant' => function (array $attributes) {
                $fraction = fake()->randomFloat(2, 0, 0.05); // 0%–5% of adult
                $price = $attributes['price_adult'] * $fraction;
                
                // Round to nearest 50,000
                return round($price / 50000) * 50000;
            },

            
            // THỜI GIAN
            'duration_days' => function (array $attributes) {
                 // Extract duration from name if possible, or random. 
                 // But name depends on duration. 
                 // To avoid circular dependency, let's just parse it from name or regenerate.
                 // Simpler: Generate duration first? No, attributes are evaluated in order? 
                 // Actually, 'name' closure generated a duration but didn't save it.
                 // Let's just random it again or parse it. Parsing is safer.
                 if (preg_match('/(\d+) Ngày/', $attributes['name'], $matches)) {
                     return (int)$matches[1];
                 }
                 return fake()->numberBetween(2, 7);
            },
            'duration_nights' => function (array $attributes) {
                return $attributes['duration_days'] - 1;
            },
            'max_people' => fake()->numberBetween(15, 40),
            
            // NGÀY KHỞI HÀNH - Đã bỏ theo mô hình Daily Departure
            // 'start_date' => fake()->dateTimeBetween('+1 week', '+1 year'),
            // 'end_date' => ...
            
            // HÌNH ẢNH
            'thumbnail' => function (array $attributes) {
                $destination = Destination::find($attributes['destination_id']);
                $slug = $destination->slug ?? 'default';
                
                $destinationsData = \Database\Seeders\DestinationData::getDestinations();
                $images = $destinationsData[$slug]['images'] ?? \Database\Seeders\DestinationData::getDefaultImages();
                
                return fake()->randomElement($images);
            },

            // TRẠNG THÁI VÀ ĐẶC TRƯNG
            'status' => fake()->randomElement(['active']),
            'featured' => fake()->boolean(30),
        ];
    }
}