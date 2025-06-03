<?php

namespace Database\Factories;

use App\Models\Reimbursement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReimbursementPayment>
 */
class ReimbursementPaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reimbursement_id' => Reimbursement::factory(),
            'paid_at' => fake()->dateTimeThisMonth(),
            'description' => fake('id')->sentence(3),
            'nominal' => fake('id')->randomNumber(6),
        ];
    }
}
