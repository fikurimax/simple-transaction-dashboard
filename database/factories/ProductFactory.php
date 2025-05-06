<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_name' => fake()->name,
            'sku' => uniqid(),
            'stock' => random_int(1, 100),
            'brand' => fake()->colorName(),
            'category' => fake()->monthName,
            'base_price' => random_int(10_000, 100_000),
            'sell_price' => random_int(10_000, 100_000),
            'unit' => 'pc',
            'description' => fake()->text,
            'submitted_by' => User::factory(),
        ];
    }
}
