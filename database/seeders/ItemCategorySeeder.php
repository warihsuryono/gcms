<?php

namespace Database\Seeders;

use App\Models\ItemCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ItemCategory::create(['name' => 'Perlengkapan Kantor']);
        ItemCategory::create(['name' => 'Spareparts']);
        ItemCategory::create(['name' => 'Tools']);
        ItemCategory::create(['name' => 'Tenaga Ahli']);
        ItemCategory::create(['name' => 'Pemeliharaan']);
    }
}
