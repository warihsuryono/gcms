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
        ItemType::create(['name' => 'Alat Tulis Kantor']);
        ItemType::create(['name' => 'Amenities']);
        ItemType::create(['name' => 'Meja']);
        ItemType::create(['name' => 'Kursi']);
        ItemType::create(['name' => 'White Board']);
        ItemType::create(['name' => 'Tinta Printer']);
    }
}
