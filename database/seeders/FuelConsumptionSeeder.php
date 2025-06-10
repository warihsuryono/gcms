<?php

namespace Database\Seeders;

use App\Models\FuelConsumption;
use Illuminate\Database\Seeder;

class FuelConsumptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FuelConsumption::create(['consumption_at' => '2024-01-01', 'item_type_id' => 1, 'quantity' => 3545.73, 'unit_id' => 4]);
        FuelConsumption::create(['consumption_at' => '2024-02-01', 'item_type_id' => 1, 'quantity' => 2614.09, 'unit_id' => 4]);
        FuelConsumption::create(['consumption_at' => '2024-03-01', 'item_type_id' => 1, 'quantity' => 2209.60, 'unit_id' => 4]);
        FuelConsumption::create(['consumption_at' => '2024-04-01', 'item_type_id' => 1, 'quantity' => 2364.99, 'unit_id' => 4]);
        FuelConsumption::create(['consumption_at' => '2024-05-01', 'item_type_id' => 1, 'quantity' => 4243.88, 'unit_id' => 4]);
        FuelConsumption::create(['consumption_at' => '2024-06-01', 'item_type_id' => 1, 'quantity' => 2804.41, 'unit_id' => 4]);
        FuelConsumption::create(['consumption_at' => '2024-07-01', 'item_type_id' => 1, 'quantity' => 2447.26, 'unit_id' => 4]);
        FuelConsumption::create(['consumption_at' => '2024-08-01', 'item_type_id' => 1, 'quantity' => 2138.49, 'unit_id' => 4]);
        FuelConsumption::create(['consumption_at' => '2024-09-01', 'item_type_id' => 1, 'quantity' => 2153.94, 'unit_id' => 4]);
        FuelConsumption::create(['consumption_at' => '2024-10-01', 'item_type_id' => 1, 'quantity' => 2631.23, 'unit_id' => 4]);
        FuelConsumption::create(['consumption_at' => '2024-11-01', 'item_type_id' => 1, 'quantity' => 2055.44, 'unit_id' => 4]);
        FuelConsumption::create(['consumption_at' => '2024-12-01', 'item_type_id' => 1, 'quantity' => 2491.30, 'unit_id' => 4]);
        FuelConsumption::create(['consumption_at' => '2025-01-01', 'item_type_id' => 1, 'quantity' => 3061.96, 'unit_id' => 4]);
        FuelConsumption::create(['consumption_at' => '2025-02-01', 'item_type_id' => 1, 'quantity' => 3695.80, 'unit_id' => 4]);
        FuelConsumption::create(['consumption_at' => '2025-03-01', 'item_type_id' => 1, 'quantity' => 2989.61, 'unit_id' => 4]);
        FuelConsumption::create(['consumption_at' => '2024-01-01', 'item_type_id' => 2, 'quantity' => 3802, 'unit_id' => 4]);
        FuelConsumption::create(['consumption_at' => '2024-02-01', 'item_type_id' => 2, 'quantity' => 4381, 'unit_id' => 4]);
        FuelConsumption::create(['consumption_at' => '2024-03-01', 'item_type_id' => 2, 'quantity' => 3940, 'unit_id' => 4]);
        FuelConsumption::create(['consumption_at' => '2024-04-01', 'item_type_id' => 2, 'quantity' => 3477, 'unit_id' => 4]);
        FuelConsumption::create(['consumption_at' => '2024-05-01', 'item_type_id' => 2, 'quantity' => 4439, 'unit_id' => 4]);
        FuelConsumption::create(['consumption_at' => '2024-06-01', 'item_type_id' => 2, 'quantity' => 5465, 'unit_id' => 4]);
        FuelConsumption::create(['consumption_at' => '2024-07-01', 'item_type_id' => 2, 'quantity' => 6355, 'unit_id' => 4]);
        FuelConsumption::create(['consumption_at' => '2024-08-01', 'item_type_id' => 2, 'quantity' => 5475, 'unit_id' => 4]);
        FuelConsumption::create(['consumption_at' => '2024-09-01', 'item_type_id' => 2, 'quantity' => 5310, 'unit_id' => 4]);
        FuelConsumption::create(['consumption_at' => '2024-10-01', 'item_type_id' => 2, 'quantity' => 5563, 'unit_id' => 4]);
        FuelConsumption::create(['consumption_at' => '2024-11-01', 'item_type_id' => 2, 'quantity' => 4891, 'unit_id' => 4]);
        FuelConsumption::create(['consumption_at' => '2024-12-01', 'item_type_id' => 2, 'quantity' => 4743, 'unit_id' => 4]);
        FuelConsumption::create(['consumption_at' => '2025-01-01', 'item_type_id' => 2, 'quantity' => 5144, 'unit_id' => 4]);
        FuelConsumption::create(['consumption_at' => '2025-02-01', 'item_type_id' => 2, 'quantity' => 1565, 'unit_id' => 4]);
        FuelConsumption::create(['consumption_at' => '2025-03-01', 'item_type_id' => 2, 'quantity' => 5603, 'unit_id' => 4]);
    }
}
