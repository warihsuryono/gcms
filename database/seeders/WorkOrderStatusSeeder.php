<?php

namespace Database\Seeders;

use App\Models\WorkOrderStatus;
use Illuminate\Database\Seeder;

class WorkOrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WorkOrderStatus::create(['name' => 'On Schedule']);
        WorkOrderStatus::create(['name' => 'On Progress']);
        WorkOrderStatus::create(['name' => 'Delay']);
        WorkOrderStatus::create(['name' => 'Pending']);
    }
}
