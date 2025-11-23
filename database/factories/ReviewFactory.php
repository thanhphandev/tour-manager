<?php

namespace Database\Factories;

use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'rating'        => $this->faker->numberBetween(3, 5),
            'title'         => $this->faker->sentence(),
            'comment'       => $this->faker->paragraph(),
            'is_verified'   => true,
            'is_approved'   => true,
            'helpful_count' => $this->faker->numberBetween(0, 50),
            'created_at'    => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
