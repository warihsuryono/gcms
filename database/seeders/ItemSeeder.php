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
        Item::create(['item_specification_id' => 1, 'item_category_id' => 1, 'item_type_id' => 1, 'item_brand_id' => 1, 'name' => 'Gasoline', 'unit_id' => 4, 'description' => 'Gasoline for vehicle operational', 'minimum_stock' => 100, 'maximum_stock' => 500, 'lifetime' => '999']);
        Item::create(['item_specification_id' => 1, 'item_category_id' => 1, 'item_type_id' => 2, 'item_brand_id' => 1, 'name' => 'Diesel', 'unit_id' => 4, 'description' => 'Diesel for diesel engine operational', 'minimum_stock' => 100, 'maximum_stock' => 500, 'lifetime' => '999']);
    }
}
