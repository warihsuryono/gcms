<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\Unit;
use App\Models\PurchaseOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PurchaseOrderDetail>
 */
class PurchaseOrderDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'purchase_order_id' => PurchaseOrder::factory(),
            'seqno' => fake()->randomDigit(),
            'item_id' => Item::all()->random(),
            'qty' => fake()->randomDigit(),
            'unit_id' => Unit::all()->random(),
            'price' => fake()->randomNumber(8, true),
            'notes' =>  fake('id')->sentence(3),
        ];
    }
}
