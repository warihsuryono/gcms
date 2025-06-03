<?php

namespace Database\Factories;

use App\Models\ItemBrand;
use App\Models\ItemCategory;
use App\Models\ItemSpecification;
use App\Models\ItemType;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'item_specification_id' => ItemSpecification::all()->random()->first(),
            'item_category_id' => ItemCategory::all()->random()->first(),
            'item_type_id' => ItemType::all()->random()->first(),
            'item_brand_id' => ItemBrand::all()->random()->first(),
            'name' => fake('id')->name(),
            'unit_id' => Unit::all()->random()->first(),
            'description' => fake('id')->sentence(5),
            'minimum_stock' => 1,
            'maximum_stock' => 5,
            'lifetime' => 24,
        ];
    }
}
