<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            privileges::class,
            users::class,
            icons::class,
            BankSeeder::class,
            CurrencySeeder::class,
            FollowupOfficerSeeder::class,
            ProvinceSeeder::class,
            CitySeeder::class,
            SupplierSeeder::class,
            UnitSeeder::class,
            PaymentTypeSeeder::class,
            ItemSpecificationSeeder::class,
            ItemCategorySeeder::class,
            ItemTypeSeeder::class,
            ItemBrandSeeder::class,
            ItemSeeder::class,
            DivisionSeeder::class,
            FieldSeeder::class,
            DashboardSeeder::class,
            FuelConsumptionSeeder::class,
            WorkOrderSeeder::class,
            ItemMovementTypeSeeder::class,
            ItemRequestTypeSeeder::class,
            WorkOrderStatusSeeder::class,
            WarehouseSeeder::class,
            FuelpoweredEquipmentSeeder::class,
            StockSeeder::class,
            // DegreeSeeder::class,
            // MarriageStatusSeeder::class,
            // EmployeeStatusSeeder::class,
            // EmployeeSeeder::class,
            // LeaveTypeSeeder::class,
        ]);
    }
}
