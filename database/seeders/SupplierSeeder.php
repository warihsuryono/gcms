<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Supplier::create([
            'import_domestic' => 'domestic',
            'name' => 'PT. Tokopedia',
            'pic' => 'Tokopedia Care',
            'pic_phone' => '',
            'email' => 'care@tokopedia.com',
            'address' => 'TOKOPEDIA CARE TOWER - Ground Floor Ciputra International, Jl. Lkr. Luar Barat No.101, RT.13, Rw. Buaya, Kecamatan Cengkareng',
            'city_id' => '29',
            'province_id' => '1',
            'country' => 'Indonesia',
            'zipcode' => '11740',
            'fax' => '',
            'payment_type_id' => '1',
            'nationality' => 'Indonesia'
        ]);
    }
}
