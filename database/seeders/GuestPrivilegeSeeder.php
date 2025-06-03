<?php

namespace Database\Seeders;

use App\Models\Privilege;
use Illuminate\Database\Seeder;

class GuestPrivilegeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Privilege::create(['id' => 7, 'name' => 'Guest', 'menu_ids' => '1,7,59', 'privileges' => '15,15,15']);
    }
}
