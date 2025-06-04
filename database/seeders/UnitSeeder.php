<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Unit::create(['name' => 'Pcs']);
        Unit::create(['name' => 'Unit']);
        Unit::create(['name' => 'Set']);
        Unit::create(['name' => 'Litre']);
        Unit::create(['name' => 'Meter']);
        Unit::create(['name' => 'gram']);
        Unit::create(['name' => 'm2']);
        Unit::create(['name' => 'Dozen']);
        Unit::create(['name' => 'Box']);
        Unit::create(['name' => 'Roll']);
        Unit::create(['name' => 'Pack']);
        Unit::create(['name' => 'Sheet']);
        Unit::create(['name' => 'Stem']);
        Unit::create(['name' => 'cm']);
        Unit::create(['name' => 'Km']);
        Unit::create(['name' => 'cm2']);
        Unit::create(['name' => 'km2']);
        Unit::create(['name' => 'cm3']);
        Unit::create(['name' => 'm3']);
        Unit::create(['name' => 'km3']);
        Unit::create(['name' => 'cc']);
        Unit::create(['name' => 'ml']);
        Unit::create(['name' => 'galon']);
        Unit::create(['name' => 'bottle']);
        Unit::create(['name' => 'Kg']);
        Unit::create(['name' => 'quintal']);
        Unit::create(['name' => 'ton']);
        Unit::create(['name' => 'm/s']);
        Unit::create(['name' => 'Km/h']);
        Unit::create(['name' => 'µg/cm3']);
        Unit::create(['name' => 'µg/m3']);
        Unit::create(['name' => 'g/cm3']);
        Unit::create(['name' => 'g/m3']);
        Unit::create(['name' => '%']);
        Unit::create(['name' => 'ppb']);
        Unit::create(['name' => 'ppm']);
        Unit::create(['name' => 'second']);
        Unit::create(['name' => 'minute']);
        Unit::create(['name' => 'hour']);
        Unit::create(['name' => 'day']);
        Unit::create(['name' => 'month']);
        Unit::create(['name' => 'year']);
        Unit::create(['name' => 'manday']);
    }
}
