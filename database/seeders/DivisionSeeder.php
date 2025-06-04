<?php

namespace Database\Seeders;

use App\Models\Division;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Division::create(['name' => 'Landscape']);
        Division::create(['name' => 'Construction']);
        Division::create(['name' => 'Irrigation']);
        Division::create(['name' => 'Operator']);
        Division::create(['name' => 'Mechanical']);
        Division::create(['name' => 'Electrical']);
        Division::create(['name' => 'Civil']);
        Division::create(['name' => 'Store Keeper']);
    }
}
