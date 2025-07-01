<?php

namespace Database\Seeders;

use App\Models\StockOpname;
use App\Models\StockOpnameDetail;
use Illuminate\Database\Seeder;

class StockOpnameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StockOpname::create([
            'id' => 2,
            'stock_opname_at' => NOW(),
            'warehouse_id' => 1,
            'notes' => 'Monthly stock opname for warehouse 1',
            'created_by' => 6,
            'created_at' => now(),
        ]);
        StockOpnameDetail::create(['stock_opname_id' => 2, 'item_id' => 1, 'unit_id' => 4, 'qty' => 100, 'actual_qty' => 90, 'notes' => 'evaporation']);
        StockOpnameDetail::create(['stock_opname_id' => 2, 'item_id' => 2, 'unit_id' => 4, 'qty' => 90, 'actual_qty' => 88, 'notes' => 'evaporation']);
        StockOpnameDetail::create(['stock_opname_id' => 2, 'item_id' => 3, 'unit_id' => 10, 'qty' => 10, 'actual_qty' => 9, 'notes' => 'Repack for FERT-02']);
        StockOpnameDetail::create(['stock_opname_id' => 2, 'item_id' => 4, 'unit_id' => 10, 'qty' => 20, 'actual_qty' => 28, 'notes' => 'Repack from FERT-01']);
    }
}
