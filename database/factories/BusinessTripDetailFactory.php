<?php

namespace Database\Factories;

use App\Models\BusinessTrip;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BusinessTripDetail>
 */
class BusinessTripDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'business_trip_id' => BusinessTrip::factory(),
            'description' => fake('id')->sentence(3),
            'nominal' => fake('id')->randomNumber(6),
        ];
    }
}
