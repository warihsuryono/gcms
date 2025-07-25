<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class users extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create(['id' => 1, 'privilege_id' => 1, 'email' => 'superuser@gcms.co.id', 'name' => 'Superuser', 'password' => '$2y$12$1IRiFoWN2KHGYJ5ksKKswuf3DN6uZiV5gVGOXny8e5ux2O6fWForm', 'msisdn' => '+6281212864040']);
        User::create(['id' => 2, 'privilege_id' => 2, 'email' => 'admin@gcms.co.id', 'name' => 'Administrator', 'msisdn' => '+62-', 'password' => '$2y$12$1IRiFoWN2KHGYJ5ksKKswuf3DN6uZiV5gVGOXny8e5ux2O6fWForm']);
        User::create(['id' => 3, 'privilege_id' => 3, 'email' => 'bod@gcms.co.id', 'name' => 'Board of Director', 'msisdn' => '+62-', 'password' => '$2y$12$1IRiFoWN2KHGYJ5ksKKswuf3DN6uZiV5gVGOXny8e5ux2O6fWForm']);
        User::create(['id' => 4, 'privilege_id' => 4, 'email' => 'manager@gcms.co.id', 'name' => 'Manager', 'msisdn' => '+62-', 'password' => '$2y$12$1IRiFoWN2KHGYJ5ksKKswuf3DN6uZiV5gVGOXny8e5ux2O6fWForm']);
        User::create(['id' => 5, 'privilege_id' => 5, 'email' => 'superintendent@gcms.co.id', 'name' => 'Superintendent', 'msisdn' => '+62-', 'password' => '$2y$12$1IRiFoWN2KHGYJ5ksKKswuf3DN6uZiV5gVGOXny8e5ux2O6fWForm']);
        User::create(['id' => 6, 'privilege_id' => 6, 'email' => 'store_keeper@gcms.co.id', 'name' => 'Store Keeper', 'msisdn' => '+62-', 'password' => '$2y$12$1IRiFoWN2KHGYJ5ksKKswuf3DN6uZiV5gVGOXny8e5ux2O6fWForm']);
        User::create(['id' => 7, 'privilege_id' => 7, 'email' => 'rahmat@gcms.co.id', 'name' => 'Rahmat Fauzi', 'msisdn' => '+62-', 'password' => '$2y$12$1IRiFoWN2KHGYJ5ksKKswuf3DN6uZiV5gVGOXny8e5ux2O6fWForm']);
    }
}
