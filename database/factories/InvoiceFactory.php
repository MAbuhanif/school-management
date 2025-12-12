<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'amount' => $this->faker->randomFloat(2, 10, 500),
            'status' => $this->faker->randomElement(['unpaid', 'paid', 'failed']),
            'due_date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'description' => $this->faker->sentence(),
            'stripe_session_id' => $this->faker->optional(0.3)->uuid(),
        ];
    }
}
