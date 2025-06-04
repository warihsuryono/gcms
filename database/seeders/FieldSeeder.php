<?php

namespace Database\Seeders;

use App\Models\Field;
use Illuminate\Database\Seeder;

class FieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Field::create(['name' => 'Hole 1']);
        Field::create(['name' => 'Hole 2']);
        Field::create(['name' => 'Hole 3']);
        Field::create(['name' => 'Hole 4']);
        Field::create(['name' => 'Hole 5']);
        Field::create(['name' => 'Hole 6']);
        Field::create(['name' => 'Hole 7']);
        Field::create(['name' => 'Hole 8']);
        Field::create(['name' => 'Hole 9']);
        Field::create(['name' => 'Hole 10']);
        Field::create(['name' => 'Hole 11']);
        Field::create(['name' => 'Hole 12']);
        Field::create(['name' => 'Hole 13']);
        Field::create(['name' => 'Hole 14']);
        Field::create(['name' => 'Hole 15']);
        Field::create(['name' => 'Hole 16']);
        Field::create(['name' => 'Hole 17']);
        Field::create(['name' => 'Hole 18']);
        Field::create(['name' => 'Hole 19']);
        Field::create(['name' => 'Hole 20']);
        Field::create(['name' => 'All Holes']);
        Field::create(['name' => 'Nursery']);
        Field::create(['name' => 'Club House']);
        Field::create(['name' => 'Putting Green']);
        Field::create(['name' => 'Chipping Green']);
        Field::create(['name' => 'Driving Range']);
        Field::create(['name' => 'GCM Area']);
        Field::create(['name' => 'Warehouse']);
    }
}
