<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\privilege>
 */
class PrivilegeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(1, false),
            'menu_ids' => Str::random(10),
            'privileges' => Str::random(10),
            'deleted_by' => 0,
            'created_by' => 0,
            'updated_by' => 0,
        ];
    }
}
