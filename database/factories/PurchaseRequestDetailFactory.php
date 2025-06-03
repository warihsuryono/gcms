<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\PurchaseRequest;
use App\Models\Supplier;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PurchaseRequestDetail>
 */
class PurchaseRequestDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'purchase_request_id' => PurchaseRequest::factory(),
            'seqno' => fake()->randomDigit(),
            'supplier_id' => Supplier::all()->random(),
            'item_id' => Item::all()->random(),
            'notes' =>  fake('id')->sentence(5),
            'qty' => fake()->randomDigit(),
            'unit_id' => Unit::all()->random(),
            'price' => fake()->randomNumber(8, true),
        ];
    }
}
