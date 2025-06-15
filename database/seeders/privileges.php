<?php

namespace Database\Seeders;

use App\Models\Privilege;
use Illuminate\Database\Seeder;

class privileges extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Privilege::create(['id' => 1, 'name' => 'Superuser', 'menu_ids' => '0', 'privileges' => '0']);
        Privilege::create(['id' => 2, 'name' => 'Administrator', 'menu_ids' => '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41', 'privileges' => '15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15']);
        Privilege::create(['id' => 3, 'name' => 'BOD', 'menu_ids' => '1,2,5,6,31,36,37,38,39,40,41', 'privileges' => '15,15,15,15,15,15,15,15,15,15,15']);
        Privilege::create(['id' => 4, 'name' => 'Manager', 'menu_ids' => '1,2,4,5,6,28,29,30,31,32,33,34,35,36,37,38,39,40,41', 'privileges' => '15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15']);
        Privilege::create(['id' => 5, 'name' => 'Superintendent', 'menu_ids' => '1,2,4,5,6,28,29,30,31,32,33,34,35,36,37,38,39,40,41', 'privileges' => '15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15']);
        Privilege::create(['id' => 6, 'name' => 'Stock Keeper', 'menu_ids' => '1,2,3,4,5,6,13,21,22,23,24,25,26,27,28,30,31,32,33,34,35,36,37,38,39,40,41', 'privileges' => '15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15']);
        Privilege::create(['id' => 7, 'name' => 'Staff', 'menu_ids' => '1,2,4,5,6,28,29,30,32,37', 'privileges' => '15,15,15,15,15,15,15,15,15,15']);
    }
}
