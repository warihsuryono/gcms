<?php

namespace Database\Factories;

use App\Models\Bank;
use App\Models\City;
use App\Models\Province;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BusinessTrip>
 */
class BusinessTripFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'doc_no' => fake('id')->numerify('BTR-#####'),
            'province_id' => Province::all()->random(),
            'city_id' => City::all()->random(),
            'destination' => fake('id')->sentence(2),
            'airport_destination' => fake('id')->sentence(2),
            'departure_at' => fake()->dateTimeThisMonth(),
            'arrival_at' => fake()->dateTimeThisMonth(),
            'project_name' => fake('id')->sentence(2),
            'bank_id' => Bank::all()->random(),
            'bank_account_name' => fake('id')->name(),
            'bank_account_no' => fake('id')->randomNumber(6),
            'total' => fake()->randomNumber(6),
            'is_approved' => 0,
            'approved_at' => fake()->dateTimeThisMonth(),
            'approved_by' => User::all()->random(),
            'is_acknowledge' => 0,
            'acknowledge_at' => fake()->dateTimeThisMonth(),
            'acknowledge_by' => User::all()->random(),
            'deleted_by' => 0,
        ];
    }
}
