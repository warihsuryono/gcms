<?php

namespace Database\Seeders;

use App\Models\EmployeeActivity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EmployeeActivity::create(['id' => '1', 'name' => 'Daily Work']);
        EmployeeActivity::create(['id' => '2', 'name' => 'Meeting']);
        EmployeeActivity::create(['id' => '3', 'name' => 'Training']);
        EmployeeActivity::create(['id' => '4', 'name' => 'Webinar']);
        EmployeeActivity::create(['id' => '5', 'name' => 'Customer Visit']);
        EmployeeActivity::create(['id' => '6', 'name' => 'Site Visit']);
        EmployeeActivity::create(['id' => '7', 'name' => 'Other']);
    }
}
