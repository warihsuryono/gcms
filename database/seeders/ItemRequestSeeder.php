<?php

namespace Database\Seeders;

use App\Models\ItemRequest;
use App\Models\ItemRequestDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ItemRequest::create(['item_request_no' => 'IR/VI/2025/001', 'item_request_at' => NOW(), 'user_id' => 7, 'work_order_id' => 2, 'description' => '
        • pupuk semprot semua rumput green<br>
        • bahan : ditnahe m 45<br>
        • alat : SDI<br>
        ']);
        ItemRequestDetail::create(['item_request_id' => 1, 'seqno' => 1, 'item_request_type_id' => 1, 'item_id' => 13, 'unit_id' => 10, 'qty' => 6]);
        ItemRequestDetail::create(['item_request_id' => 1, 'seqno' => 2, 'item_request_type_id' => 1, 'item_id' => 12, 'unit_id' => 10, 'qty' => 2]);
        ItemRequestDetail::create(['item_request_id' => 1, 'seqno' => 3, 'item_request_type_id' => 1, 'item_id' => 2, 'unit_id' => 4, 'qty' => 2]);
    }
}
