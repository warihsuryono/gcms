<?php

namespace Database\Factories;

use App\Models\Currency;
use App\Models\PaymentType;
use App\Models\PurchaseRequest;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PurchaseOrder>
 */
class PurchaseOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'doc_no' => fake('id')->numerify('PO-#####'),
            'doc_at' => fake('id')->dateTimeThisMonth(),
            'supplier_id' => Supplier::all()->random(),
            'delivery_at' => fake('id')->dateTimeThisMonth(),
            'payment_type_id' => PaymentType::all()->random(),
            'purchase_request_id' => PurchaseRequest::all()->random(),
            'use_by' => User::all()->random(),
            'use_at' => fake('id')->dateTimeThisMonth(),
            'shipment_pic' => fake('id')->name(),
            'shipment_address' => fake('id')->address(),
            'currency_id' => Currency::all()->random(),
            'discount_is_percentage' => 1,
            'discount' => rand(0, 5),
            'tax' => '10',
            'created_by' => User::all()->random(),
        ];
    }
}
