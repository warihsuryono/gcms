<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use App\Models\WarehouseDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Warehouse::create([
            'id' => 1,
            'name' => 'Main Warehouse',
            'location' => '123 Main St, Cityville',
            'description' => 'The main warehouse for all operations.',
            'capacity' => 1000,
            'pic' => 6,
        ]);
        WarehouseDetail::create([
            'warehouse_id' => 1,
            'code' => 'WH001A1R1L1S1',
            'aisle' => 'A1',
            'rack' => 'R1',
            'level' => 'L1',
            'slot' => 'S1',
            'notes' => 'Main storage area for high-demand items.',
        ]);
        WarehouseDetail::create([
            'warehouse_id' => 1,
            'code' => 'WH001A1R1L1S2',
            'aisle' => 'A1',
            'rack' => 'R1',
            'level' => 'L1',
            'slot' => 'S2',
            'notes' => 'Secondary storage area for overflow items.',
        ]);
        WarehouseDetail::create([
            'warehouse_id' => 1,
            'code' => 'WH001A1R1L2S1',
            'aisle' => 'A1',
            'rack' => 'R1',
            'level' => 'L2',
            'slot' => 'S1',
            'notes' => 'Storage for seasonal items.',
        ]);
        WarehouseDetail::create([
            'warehouse_id' => 1,
            'code' => 'WH001A1R1L2S2',
            'aisle' => 'A1',
            'rack' => 'R1',
            'level' => 'L2',
            'slot' => 'S2',
            'notes' => 'Storage for fragile items.',
        ]);
    }
}
