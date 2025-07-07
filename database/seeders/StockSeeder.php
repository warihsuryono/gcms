<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\StockOpname;
use App\Models\StockOpnameDetail;
use App\Models\ItemMovement;
use App\Models\ItemStock;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StockOpname::create([
            'id' => '1',
            'stock_opname_at' => NOW(),
            'warehouse_id' => 1,
            'notes' => 'initial stock',
            'is_approved' => 1,
            'approved_at' => NOW(),
            'approved_by' => 6
        ]);

        StockOpnameDetail::create(['stock_opname_id' => 1, 'item_id' => 1, 'unit_id' => 4, 'qty' => 100, 'actual_qty' => 0, 'notes' => Item::find(1)->name . ' initial stock']);
        StockOpnameDetail::create(['stock_opname_id' => 1, 'item_id' => 2, 'unit_id' => 4, 'qty' => 90, 'actual_qty' => 0, 'notes' => Item::find(2)->name . ' initial stock']);
        StockOpnameDetail::create(['stock_opname_id' => 1, 'item_id' => 3, 'unit_id' => 2, 'qty' => 5, 'actual_qty' => 0, 'notes' => Item::find(3)->name . ' initial stock']);
        StockOpnameDetail::create(['stock_opname_id' => 1, 'item_id' => 4, 'unit_id' => 2, 'qty' => 3, 'actual_qty' => 0, 'notes' => Item::find(4)->name . ' initial stock']);
        StockOpnameDetail::create(['stock_opname_id' => 1, 'item_id' => 5, 'unit_id' => 10, 'qty' => 10, 'actual_qty' => 0, 'notes' => Item::find(5)->name . ' initial stock']);
        StockOpnameDetail::create(['stock_opname_id' => 1, 'item_id' => 6, 'unit_id' => 10, 'qty' => 20, 'actual_qty' => 0, 'notes' => Item::find(6)->name . ' initial stock']);
        StockOpnameDetail::create(['stock_opname_id' => 1, 'item_id' => 7, 'unit_id' => 10, 'qty' => 30, 'actual_qty' => 0, 'notes' => Item::find(7)->name . ' initial stock']);
        StockOpnameDetail::create(['stock_opname_id' => 1, 'item_id' => 8, 'unit_id' => 10, 'qty' => 25, 'actual_qty' => 0, 'notes' => Item::find(8)->name . ' initial stock']);
        StockOpnameDetail::create(['stock_opname_id' => 1, 'item_id' => 9, 'unit_id' => 10, 'qty' => 20, 'actual_qty' => 0, 'notes' => Item::find(9)->name . ' initial stock']);
        StockOpnameDetail::create(['stock_opname_id' => 1, 'item_id' => 10, 'unit_id' => 10, 'qty' => 15, 'actual_qty' => 0, 'notes' => Item::find(10)->name . ' initial stock']);
        StockOpnameDetail::create(['stock_opname_id' => 1, 'item_id' => 11, 'unit_id' => 10, 'qty' => 10, 'actual_qty' => 0, 'notes' => Item::find(11)->name . ' initial stock']);
        StockOpnameDetail::create(['stock_opname_id' => 1, 'item_id' => 12, 'unit_id' => 10, 'qty' => 5, 'actual_qty' => 0, 'notes' => Item::find(12)->name . ' initial stock']);
        StockOpnameDetail::create(['stock_opname_id' => 1, 'item_id' => 13, 'unit_id' => 10, 'qty' => 1, 'actual_qty' => 0, 'notes' => Item::find(13)->name . ' initial stock']);
        StockOpnameDetail::create(['stock_opname_id' => 1, 'item_id' => 14, 'unit_id' => 10, 'qty' => 2, 'actual_qty' => 0, 'notes' => Item::find(14)->name . ' initial stock']);

        ItemMovement::create(['movement_at' => NOW(), 'in_out' => 'in', 'item_movement_type_id' => 4, 'item_id' => 1, 'qty' => 100, 'unit_id' => 4, 'notes' => Item::find(1)->name . ' initial stock']);
        ItemMovement::create(['movement_at' => NOW(), 'in_out' => 'in', 'item_movement_type_id' => 4, 'item_id' => 2, 'qty' => 90, 'unit_id' => 4, 'notes' => Item::find(2)->name . ' initial stock']);
        ItemMovement::create(['movement_at' => NOW(), 'in_out' => 'in', 'item_movement_type_id' => 4, 'item_id' => 3, 'qty' => 5, 'unit_id' => 2, 'notes' => Item::find(3)->name . ' initial stock']);
        ItemMovement::create(['movement_at' => NOW(), 'in_out' => 'in', 'item_movement_type_id' => 4, 'item_id' => 4, 'qty' => 3, 'unit_id' => 2, 'notes' => Item::find(4)->name . ' initial stock']);
        ItemMovement::create(['movement_at' => NOW(), 'in_out' => 'in', 'item_movement_type_id' => 4, 'item_id' => 5, 'qty' => 10, 'unit_id' => 10, 'notes' => Item::find(5)->name . ' initial stock']);
        ItemMovement::create(['movement_at' => NOW(), 'in_out' => 'in', 'item_movement_type_id' => 4, 'item_id' => 6, 'qty' => 20, 'unit_id' => 10, 'notes' => Item::find(6)->name . ' initial stock']);
        ItemMovement::create(['movement_at' => NOW(), 'in_out' => 'in', 'item_movement_type_id' => 4, 'item_id' => 7, 'qty' => 30, 'unit_id' => 10, 'notes' => Item::find(7)->name . ' initial stock']);
        ItemMovement::create(['movement_at' => NOW(), 'in_out' => 'in', 'item_movement_type_id' => 4, 'item_id' => 8, 'qty' => 25, 'unit_id' => 10, 'notes' => Item::find(8)->name . ' initial stock']);
        ItemMovement::create(['movement_at' => NOW(), 'in_out' => 'in', 'item_movement_type_id' => 4, 'item_id' => 9, 'qty' => 20, 'unit_id' => 10, 'notes' => Item::find(9)->name . ' initial stock']);
        ItemMovement::create(['movement_at' => NOW(), 'in_out' => 'in', 'item_movement_type_id' => 4, 'item_id' => 10, 'qty' => 15, 'unit_id' => 10, 'notes' => Item::find(10)->name . ' initial stock']);
        ItemMovement::create(['movement_at' => NOW(), 'in_out' => 'in', 'item_movement_type_id' => 4, 'item_id' => 11, 'qty' => 10, 'unit_id' => 10, 'notes' => Item::find(11)->name . ' initial stock']);
        ItemMovement::create(['movement_at' => NOW(), 'in_out' => 'in', 'item_movement_type_id' => 4, 'item_id' => 12, 'qty' => 5, 'unit_id' => 10, 'notes' => Item::find(12)->name . ' initial stock']);
        ItemMovement::create(['movement_at' => NOW(), 'in_out' => 'in', 'item_movement_type_id' => 4, 'item_id' => 13, 'qty' => 1, 'unit_id' => 10, 'notes' => Item::find(13)->name . ' initial stock']);
        ItemMovement::create(['movement_at' => NOW(), 'in_out' => 'in', 'item_movement_type_id' => 4, 'item_id' => 14, 'qty' => 2, 'unit_id' => 10, 'notes' => Item::find(14)->name . ' initial stock']);

        ItemStock::create(['item_id' => 1, 'warehouse_detail_ids' => Item::find(1)->warehouse_detail_ids, 'qty' => 100]);
        ItemStock::create(['item_id' => 2, 'warehouse_detail_ids' => Item::find(2)->warehouse_detail_ids, 'qty' => 90]);
        ItemStock::create(['item_id' => 3, 'warehouse_detail_ids' => Item::find(3)->warehouse_detail_ids, 'qty' => 5]);
        ItemStock::create(['item_id' => 4, 'warehouse_detail_ids' => Item::find(4)->warehouse_detail_ids, 'qty' => 3]);
        ItemStock::create(['item_id' => 5, 'warehouse_detail_ids' => Item::find(5)->warehouse_detail_ids, 'qty' => 10]);
        ItemStock::create(['item_id' => 6, 'warehouse_detail_ids' => Item::find(6)->warehouse_detail_ids, 'qty' => 20]);
        ItemStock::create(['item_id' => 7, 'warehouse_detail_ids' => Item::find(7)->warehouse_detail_ids, 'qty' => 30]);
        ItemStock::create(['item_id' => 8, 'warehouse_detail_ids' => Item::find(8)->warehouse_detail_ids, 'qty' => 25]);
        ItemStock::create(['item_id' => 9, 'warehouse_detail_ids' => Item::find(9)->warehouse_detail_ids, 'qty' => 20]);
        ItemStock::create(['item_id' => 10, 'warehouse_detail_ids' => Item::find(10)->warehouse_detail_ids, 'qty' => 15]);
        ItemStock::create(['item_id' => 11, 'warehouse_detail_ids' => Item::find(11)->warehouse_detail_ids, 'qty' => 10]);
        ItemStock::create(['item_id' => 12, 'warehouse_detail_ids' => Item::find(12)->warehouse_detail_ids, 'qty' => 5]);
        ItemStock::create(['item_id' => 13, 'warehouse_detail_ids' => Item::find(13)->warehouse_detail_ids, 'qty' => 2]);
        ItemStock::create(['item_id' => 14, 'warehouse_detail_ids' => Item::find(14)->warehouse_detail_ids, 'qty' => 2]);
    }
}
