<?php

namespace Database\Seeders;

use App\Models\Province;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Province::create(['name' => 'DKI Jakarta']);
        Province::create(['name' => 'Bogor']);
        Province::create(['name' => 'Tanggerang']);
        Province::create(['name' => 'Depok']);
        Province::create(['name' => 'Bekasi']);
        Province::create(['name' => 'Banten']);
        Province::create(['name' => 'Jawa Barat']);
        Province::create(['name' => 'Jawa Tengah']);
        Province::create(['name' => 'Jawa Timur']);
        Province::create(['name' => 'DI Yogyakarta']);
        Province::create(['name' => 'Bali']);
        Province::create(['name' => 'DI Aceh']);
        Province::create(['name' => 'Bengkulu']);
        Province::create(['name' => 'Gorontalo']);
        Province::create(['name' => 'Jambi']);
        Province::create(['name' => 'Sumatera Barat']);
        Province::create(['name' => 'Sumatera Selatan']);
        Province::create(['name' => 'Sumatera Utara']);
        Province::create(['name' => 'Riau']);
        Province::create(['name' => 'Lampung']);
        Province::create(['name' => 'Kalimantan Barat']);
        Province::create(['name' => 'Kalimantan Selatan']);
        Province::create(['name' => 'Kalimantan Tengah']);
        Province::create(['name' => 'Kalimanta Timur']);
        Province::create(['name' => 'Kepulauan Bangka Belitung']);
        Province::create(['name' => 'Kepulauan Riau']);
        Province::create(['name' => 'Maluku']);
        Province::create(['name' => 'Maluku Utara']);
        Province::create(['name' => 'Sulawesi Barat']);
        Province::create(['name' => 'Sulawesi Selatan']);
        Province::create(['name' => 'Sulawesi Tengah']);
        Province::create(['name' => 'Sulawesi Tenggara']);
        Province::create(['name' => 'Sulawesi Utara']);
        Province::create(['name' => 'Nusa Tenggara Barat']);
        Province::create(['name' => 'Nusa Tenggara Timur']);
        Province::create(['name' => 'Papua']);
        Province::create(['name' => 'Kalimantan Utara']);
        Province::create(['name' => 'Papua Barat']);
        Province::create(['name' => 'Singapura']);
    }
}
