<?php

namespace Database\Seeders;

use App\Models\MarriageStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MarriageStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MarriageStatus::create(['id' => '1', 'name' => 'K0', 'description' => 'Kawin tidak menanggung anak']);
        MarriageStatus::create(['id' => '2', 'name' => 'K1', 'description' => 'Kawin anak 1']);
        MarriageStatus::create(['id' => '3', 'name' => 'K2', 'description' => 'Kawin anak 2']);
        MarriageStatus::create(['id' => '4', 'name' => 'K3', 'description' => 'Kawin anak 3']);
        MarriageStatus::create(['id' => '5', 'name' => 'TK0', 'description' => 'Tidak Kawin tidak menanggung anak']);
        MarriageStatus::create(['id' => '6', 'name' => 'TK1', 'description' => 'Tidak Kawin anak 1']);
        MarriageStatus::create(['id' => '7', 'name' => 'TK2', 'description' => 'Tidak Kawin anak 2']);
        MarriageStatus::create(['id' => '8', 'name' => 'TK3', 'description' => 'Tidak Kawin anak 3']);
    }
}
