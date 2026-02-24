<?php

namespace Database\Factories;

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
            'invoice_id' => \App\Models\Invoice::factory(),
            'tenant_id' => fake()->numberBetween(1, 10),
            'amount' => fake()->randomFloat(2, 100, 1000),
            'payment_method' => fake()->randomElement(\App\Enums\PaymentMethod::cases()),
            'reference_number' => 'REF-' . fake()->unique()->numerify('########'),
            'paid_at' => now(),
        ];
    }
}
