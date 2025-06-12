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
        User::create(['id' => '1', 'privilege_id' => 1, 'email' => 'superuser@gcms.co.id', 'name' => 'Superuser', 'password' => '$2y$12$.lK978LeVq9lRWnwWIMILeQj2/Xh43nB.9Xda94ngtK4HDAsteOj6', 'msisdn' => '+6281212864040']);
        User::create(['id' => '2', 'privilege_id' => 2, 'email' => 'admin@gcms.co.id', 'name' => 'Administrator', 'msisdn' => '+62-', 'password' => '$2y$12$.lK978LeVq9lRWnwWIMILeQj2/Xh43nB.9Xda94ngtK4HDAsteOj6']);
        User::create(['id' => '3', 'privilege_id' => 5, 'email' => 'rahmat@gcms.co.id', 'name' => 'Rahmat Fauzi', 'msisdn' => '+62-', 'password' => '$2y$12$.lK978LeVq9lRWnwWIMILeQj2/Xh43nB.9Xda94ngtK4HDAsteOj6']);
    }
}
