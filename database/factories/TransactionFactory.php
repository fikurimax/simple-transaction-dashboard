<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'serial' => uniqid(),
            'customer_name' => fake()->name,
            'customer_address' => fake()->address,
            'total_items' => random_int(1, 100),
            'total_quantity' => random_int(1, 100),
            'total_price' => random_int(10_000, 100_000),
            'tax' => random_int(10_000, 100_000),
            'cash_in' => random_int(10_000, 100_000),
            'change' => random_int(10_000, 100_000),
            'is_debt' => random_int(0, 1),
            'is_debt_paid' => random_int(0, 1),
            'submitted_by' => User::factory(),
        ];
    }
}
