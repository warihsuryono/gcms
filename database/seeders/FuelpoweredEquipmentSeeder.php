<?php

namespace Database\Seeders;

use App\Models\FuelpoweredEquipment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FuelpoweredEquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FuelpoweredEquipment::create(['item_type_id' => 2, 'name' => 'Tractor - KIOTI']);
        FuelpoweredEquipment::create(['item_type_id' => 2, 'name' => 'Tractor - AGROLUX']);
        FuelpoweredEquipment::create(['item_type_id' => 2, 'name' => 'Dump Truck - B9627UVZ']);
        FuelpoweredEquipment::create(['item_type_id' => 2, 'name' => 'GENSET - DONGPENG']);
    }
}
