<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'booking_id' => Booking::factory(),
            'payment_code' => null, // Will be auto-generated
            'payment_method' => fake()->randomElement(['mock', 'vnpay', 'paypal']),
            'amount' => fake()->numberBetween(1000000, 10000000),
            'status' => 'pending',
            'transaction_data' => null,
            'transaction_id' => null,
            'paid_at' => null,
            'notes' => null,
        ];
    }

    /**
     * Indicate that the payment is successful.
     */
    public function success(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'success',
            'transaction_id' => 'MOCK-' . strtoupper(uniqid()),
            'transaction_data' => [
                'card_last4' => '1234',
                'card_name' => fake()->name(),
                'processed_at' => now()->toDateTimeString(),
                'bank' => 'Mock Bank',
            ],
            'paid_at' => now(),
        ]);
    }

    /**
     * Indicate that the payment has failed.
     */
    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'failed',
            'notes' => fake()->sentence(),
        ]);
    }

    /**
     * Indicate that the payment is refunded.
     */
    public function refunded(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'refunded',
            'transaction_id' => 'MOCK-' . strtoupper(uniqid()),
            'paid_at' => now()->subDays(5),
        ]);
    }

    /**
     * Configure the factory to use booking amount and generate payment code.
     */
    public function configure()
    {
        return $this->afterMaking(function (Payment $payment) {
            if (!$payment->payment_code) {
                $payment->payment_code = Payment::generatePaymentCode();
            }
            if ($payment->booking && !$payment->amount) {
                $payment->amount = $payment->booking->total_amount;
            }
        });
    }
}
