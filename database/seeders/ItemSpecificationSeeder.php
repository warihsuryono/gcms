<?php

namespace Database\Seeders;

use App\Models\ItemSpecification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemSpecificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ItemSpecification::create(['name' => 'Barang']);
        ItemSpecification::create(['name' => 'Jasa']);
    }
}
