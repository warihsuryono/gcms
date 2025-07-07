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
        ItemRequest::create(['item_request_no' => 'IR/VI/2025/001', 'item_request_at' => NOW(), 'user_id' => 7, 'work_order_id' => 1, 'description' => '• potong semua rumput green ( Shibaura 6 unit )<br>• pupuk semprot semua rumput green<br>• Finising pasir di tee box hitam<br>• potong rumput Fairway ( lf 570 )<br>• cuci alat kalau sudah selesai bekerja', 'created_by' => 7]);
        ItemRequestDetail::create(['item_request_id' => 1, 'seqno' => 3, 'item_movement_type_id' => 2, 'item_id' => 3, 'unit_id' => 2, 'qty' => 1]);
        ItemRequestDetail::create(['item_request_id' => 1, 'seqno' => 1, 'item_movement_type_id' => 1, 'item_id' => 6, 'unit_id' => 10, 'qty' => 5]);
    }
}
