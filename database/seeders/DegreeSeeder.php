<?php

namespace Database\Seeders;

use App\Models\Degree;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DegreeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Degree::create(['id' => '1', 'name' => 'SMA/SMK']);
        Degree::create(['id' => '2', 'name' => 'D3']);
        Degree::create(['id' => '3', 'name' => 'S1']);
        Degree::create(['id' => '4', 'name' => 'S2']);
        Degree::create(['id' => '5', 'name' => 'S3']);
    }
}
