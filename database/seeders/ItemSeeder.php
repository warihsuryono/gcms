<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Item::create(['item_specification_id' => 1, 'item_category_id' => 1, 'item_type_id' => 1, 'item_brand_id' => 3, 'name' => 'Boardmarker Blue BG-12', 'unit_id' => 1, 'description' => 'Spidol white board warna biru', 'minimum_stock' => 1, 'maximum_stock' => 5, 'lifetime' => '999']);
    }
}
