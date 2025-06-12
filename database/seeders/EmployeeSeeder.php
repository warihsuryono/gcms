<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Employee::create(['user_id' => 3, 'leader_user_id' => 0, 'division_id' => 1, 'name' => 'Rahmat Fauzi', 'gender' => 'male', 'employee_status_id' => 1, 'marriage_status_id' => 3, 'degree_id' => 3]);
    }
}
