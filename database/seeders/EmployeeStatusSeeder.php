<?php

namespace Database\Seeders;

use App\Models\EmployeeStatus;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EmployeeStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EmployeeStatus::create(['id' => '1', 'name' => 'PKWTT']);
        EmployeeStatus::create(['id' => '2', 'name' => 'PKWT']);
        EmployeeStatus::create(['id' => '3', 'name' => 'Freelance']);
        EmployeeStatus::create(['id' => '4', 'name' => 'Internship']);
    }
}
