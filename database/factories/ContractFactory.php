<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contract>
 */
class ContractFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-1 year', 'now');
        $endDate = (clone $startDate)->modify('+1 year');
        
        return [
            'tenant_id' => fake()->numberBetween(1, 10),
            'unit_name' => fake()->randomElement([
                'Unit ' . fake()->numberBetween(100, 999),
                'Apartment ' . fake()->randomLetter(),
                'Suite ' . fake()->numberBetween(1, 50),
                'Office ' . fake()->numberBetween(100, 500),
            ]),
            'customer_name' => fake()->name(),
            'rent_amount' => fake()->randomFloat(2, 1000, 10000),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => \App\Enums\ContractStatus::ACTIVE,
        ];
    }
}
