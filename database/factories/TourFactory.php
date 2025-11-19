<?php

namespace Database\Factories;

use App\Models\Tour;
use App\Models\Destination;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tour>
 */
class TourFactory extends Factory
{
    protected $model = Tour::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->sentence(4);
        
        return [
            'destination_id' => Destination::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
            'short_description' => fake()->sentence(10),
            'full_description' => fake()->paragraph(5),
            'itinerary' => $this->generateItinerary(),
            'price_adult' => fake()->numberBetween(1000000, 5000000),
            'price_child' => fake()->numberBetween(500000, 2500000),
            'price_infant' => fake()->numberBetween(0, 500000),
            'duration_days' => fake()->numberBetween(2, 7),
            'duration_nights' => fake()->numberBetween(1, 6),
            'max_people' => fake()->numberBetween(10, 50),
            'start_date' => now()->addDays(7),
            'end_date' => now()->addDays(14),
            'status' => 'active',
            'thumbnail' => null,
            'featured' => fake()->boolean(30),
        ];
    }

    /**
     * Indicate that the tour is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }

    /**
     * Indicate that the tour is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'featured' => true,
        ]);
    }

    /**
     * Indicate that the tour has ended.
     */
    public function ended(): static
    {
        return $this->state(fn (array $attributes) => [
            'start_date' => now()->subDays(14),
            'end_date' => now()->subDays(7),
        ]);
    }

    /**
     * Indicate that the tour is fully booked.
     */
    public function fullyBooked(): static
    {
        return $this->state(fn (array $attributes) => [
            'max_people' => 0,
        ]);
    }

    /**
     * Generate sample itinerary.
     */
    private function generateItinerary(): string
    {
        return "## Ngày 1: Khởi hành\n\n" . fake()->paragraph(2) .
               "\n\n## Ngày 2: Tham quan\n\n" . fake()->paragraph(2) .
               "\n\n## Ngày 3: Về nhà\n\n" . fake()->paragraph(2);
    }
}
