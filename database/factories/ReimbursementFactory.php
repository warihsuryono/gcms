<?php

namespace Database\Factories;

use App\Models\Bank;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reimbursement>
 */
class ReimbursementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::all()->random(),
            'bank_id' => Bank::all()->random(),
            'bank_account_name' => fake('id')->name(),
            'bank_account_no' => fake('id')->randomNumber(6),
            'notes' => fake('id')->sentence(3),
            'total' => fake()->randomNumber(6),
            'is_paid' => rand(0, 1),
            'is_approved' => rand(0, 1),
            'approved_at' => fake()->dateTimeThisMonth(),
            'approved_by' => rand(1, 4),
            'is_acknowledge' => rand(0, 1),
            'acknowledge_at' => fake()->dateTimeThisMonth(),
            'acknowledge_by' => rand(1, 4),
            'deleted_by' => 0,
        ];
    }
}
