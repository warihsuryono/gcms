<?php

namespace Database\Seeders;

use App\Models\ItemType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ItemType::create(['name' => 'Gasoline']);
        ItemType::create(['name' => 'Diesel']);
        ItemType::create(['name' => 'Fertilizer']);
        ItemType::create(['name' => 'Tractor']);
        ItemType::create(['name' => 'Stationery']);
        ItemType::create(['name' => 'Amenities']);
        ItemType::create(['name' => 'Desk']);
        ItemType::create(['name' => 'Chair']);
        ItemType::create(['name' => 'Cabinet']);
        ItemType::create(['name' => 'Printer']);
        ItemType::create(['name' => 'Printer ink']);
        ItemType::create(['name' => 'Scanner']);
        ItemType::create(['name' => 'Photocopy']);
        ItemType::create(['name' => 'Laptop']);
        ItemType::create(['name' => 'Computer']);
        ItemType::create(['name' => 'Projector']);
        ItemType::create(['name' => 'White Board']);
        ItemType::create(['name' => 'Boardmarker']);
        ItemType::create(['name' => 'White Board Marker']);
        ItemType::create(['name' => 'White Board Eraser']);
    }
}
