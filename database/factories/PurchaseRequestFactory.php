<?php

namespace Database\Factories;

use App\Models\Currency;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PurchaseRequest>
 */
class PurchaseRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'doc_no' => fake('id')->numerify('PR-#####'),
            'doc_at' => fake('id')->dateTimeThisMonth(),
            'use_by' => fake()->randomDigit(),
            'use_at' => fake('id')->dateTimeThisMonth(),
            'description' => fake('id')->sentence(3),
            'currency_id' => Currency::all()->random(),
            'tax' => '10',
            'created_by' => User::all()->random(),
        ];
    }
}
