<?php

namespace Database\Seeders;

use App\Models\ItemRequestType;
use Illuminate\Database\Seeder;

class ItemRequestTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ItemRequestType::create(['name' => 'Consumable']);
        ItemRequestType::create(['name' => 'Loan']);
    }
}
