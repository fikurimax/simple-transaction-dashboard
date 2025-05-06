<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CashFlow>
 */
class CashFlowFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'transaction_id' => Transaction::factory(),
            'cash_in' => random_int(10_000, 100_000),
            'cash_out' => random_int(10_000, 100_000),
            'total' =>  random_int(10_000, 100_000),
            'submitted_by' => User::factory(),
        ];
    }
}
