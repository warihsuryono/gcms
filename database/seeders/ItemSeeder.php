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
        Item::create(['item_specification_id' => 1, 'item_category_id' => 6, 'item_type_id' => 3, 'item_brand_id' => 0, 'name' => 'NPK 8-4-24(50kg)', 'unit_id' => 10, 'description' => 'Fertilizer for fields', 'minimum_stock' => 10, 'maximum_stock' => 100, 'lifetime' => '999']);
        Item::create(['item_specification_id' => 1, 'item_category_id' => 6, 'item_type_id' => 3, 'item_brand_id' => 0, 'name' => 'NPK 10-18-18 (50KG)', 'unit_id' => 10, 'description' => 'Fertilizer for fields', 'minimum_stock' => 10, 'maximum_stock' => 100, 'lifetime' => '999']);
        Item::create(['item_specification_id' => 1, 'item_category_id' => 6, 'item_type_id' => 3, 'item_brand_id' => 0, 'name' => 'NPK 15-5-8 (50KG)', 'unit_id' => 10, 'description' => 'Fertilizer for fields', 'minimum_stock' => 10, 'maximum_stock' => 100, 'lifetime' => '999']);
        Item::create(['item_specification_id' => 1, 'item_category_id' => 6, 'item_type_id' => 3, 'item_brand_id' => 0, 'name' => 'NPK 15-0-24 (50KG)', 'unit_id' => 10, 'description' => 'Fertilizer for fields', 'minimum_stock' => 10, 'maximum_stock' => 100, 'lifetime' => '999']);
        Item::create(['item_specification_id' => 1, 'item_category_id' => 6, 'item_type_id' => 3, 'item_brand_id' => 0, 'name' => 'NPK  15-0-29 (50KG)', 'unit_id' => 10, 'description' => 'Fertilizer for fields', 'minimum_stock' => 10, 'maximum_stock' => 100, 'lifetime' => '999']);
        Item::create(['item_specification_id' => 1, 'item_category_id' => 6, 'item_type_id' => 3, 'item_brand_id' => 0, 'name' => 'NPK YARA MILE 15-7-7  (50 Kg)', 'unit_id' => 10, 'description' => 'Fertilizer for fields', 'minimum_stock' => 10, 'maximum_stock' => 100, 'lifetime' => '999']);
        Item::create(['item_specification_id' => 1, 'item_category_id' => 6, 'item_type_id' => 3, 'item_brand_id' => 0, 'name' => 'NPK 15-15-15 (50 Kg)', 'unit_id' => 10, 'description' => 'Fertilizer for fields', 'minimum_stock' => 10, 'maximum_stock' => 100, 'lifetime' => '999']);
        Item::create(['item_specification_id' => 1, 'item_category_id' => 6, 'item_type_id' => 3, 'item_brand_id' => 0, 'name' => 'NPK 12-24-28+4 (50kg)', 'unit_id' => 10, 'description' => 'Fertilizer for fields', 'minimum_stock' => 10, 'maximum_stock' => 100, 'lifetime' => '999']);
        Item::create(['item_specification_id' => 1, 'item_category_id' => 6, 'item_type_id' => 3, 'item_brand_id' => 0, 'name' => 'NPK 8-4-24+4 (50kg)', 'unit_id' => 10, 'description' => 'Fertilizer for fields', 'minimum_stock' => 10, 'maximum_stock' => 100, 'lifetime' => '999']);
        Item::create(['item_specification_id' => 1, 'item_category_id' => 6, 'item_type_id' => 3, 'item_brand_id' => 0, 'name' => 'NPK 14-28-10 (50kg)', 'unit_id' => 10, 'description' => 'Fertilizer for fields', 'minimum_stock' => 10, 'maximum_stock' => 100, 'lifetime' => '999']);
        Item::create(['item_specification_id' => 1, 'item_category_id' => 6, 'item_type_id' => 3, 'item_brand_id' => 0, 'name' => 'NPK 13-25-12 (50kg)', 'unit_id' => 10, 'description' => 'Fertilizer for fields', 'minimum_stock' => 10, 'maximum_stock' => 100, 'lifetime' => '999']);
    }
}
