<?php

namespace Database\Seeders;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use Illuminate\Database\Seeder;

class PurchaseOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PurchaseOrder::create([
            'doc_no' => 'PO/VI/2025/001',
            'doc_at' => NOW(),
            'item_request_id' => 0,
            'supplier_id' => 0,
            'delivery_at' => now()->addDays(7),
            'payment_type_id' => 7,
            'use_by' => 6,
            'use_at' => now()->addDays(7),
            'shipment_pic' => '',
            'shipment_address' => '',
            'currency_id' => 1,
            'discount_is_percentage' => 0,
            'discount' => 0,
            'tax' => 10,
            'subtotal' => 5029800,
            'shipping_cost' => 0,
            'grandtotal' => 5532780,
            'notes' => 'Purchase Order created from understock item list',
            'is_approved' => 1,
            'approved_at' => now(),
            'approved_by' => 6
        ]);
        PurchaseOrderDetail::create(['purchase_order_id' => 1, 'seqno' => 0, 'item_id' => 2, 'qty' => 100, 'unit_id' => 4, 'price' => 13500, 'purchase_request_detail_id' => 0]);
        PurchaseOrderDetail::create(['purchase_order_id' => 1, 'seqno' => 1, 'item_id' => 10, 'qty' => 95, 'unit_id' => 10, 'price' => 10000, 'purchase_request_detail_id' => 0]);
        PurchaseOrderDetail::create(['purchase_order_id' => 1, 'seqno' => 2, 'item_id' => 12, 'qty' => 98, 'unit_id' => 10, 'price' => 12500, 'purchase_request_detail_id' => 0]);
        PurchaseOrderDetail::create(['purchase_order_id' => 1, 'seqno' => 3, 'item_id' => 13, 'qty' => 99, 'unit_id' => 10, 'price' => 15200, 'purchase_request_detail_id' => 0]);
    }
}
