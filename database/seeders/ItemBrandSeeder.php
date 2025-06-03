<?php

namespace Database\Seeders;

use App\Models\ItemBrand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemBrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ItemBrand::create(['name' => 'Krisbow']);
        ItemBrand::create(['name' => 'Faber Castell']);
        ItemBrand::create(['name' => 'Snowmann']);
        ItemBrand::create(['name' => 'Goojodoq']);
    }
}
