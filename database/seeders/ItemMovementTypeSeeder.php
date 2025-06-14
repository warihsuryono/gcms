<?php

namespace Database\Seeders;

use App\Models\ItemMovementType;
use Illuminate\Database\Seeder;

class ItemMovementTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ItemMovementType::create(['name' => 'Purchase']);
        ItemMovementType::create(['name' => 'Consumable']);
        ItemMovementType::create(['name' => 'Loan']);
    }
}
