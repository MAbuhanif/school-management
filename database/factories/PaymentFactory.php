<?php

namespace Database\Factories;

use App\Models\Fee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fee_id' => Fee::factory(),
            'amount' => fake()->randomFloat(2, 10, 100),
            'paid_at' => now(),
            'method' => fake()->randomElement(['cash', 'card', 'online']),
            'transaction_id' => fake()->uuid(),
        ];
    }
}
