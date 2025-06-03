<?php

namespace Database\Seeders;

use App\Models\PaymentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentType::create(['name' => 'Tunai', 'description' => '']);
        PaymentType::create(['name' => 'Cash In Advance', 'description' => '']);
        PaymentType::create(['name' => 'Termin 50%,30%,20%', 'description' => 'DP 50%; Serah terima 30%; Masa garansi 20%']);
        PaymentType::create(['name' => '7 Hari', 'description' => '7 Hari Setelah Invoice diterima']);
        PaymentType::create(['name' => '14 Hari', 'description' => '14 Hari Setelah Invoice diterima']);
        PaymentType::create(['name' => '15 Hari', 'description' => '15 Hari Setelah Invoice diterima']);
        PaymentType::create(['name' => '30 Hari', 'description' => '30 Hari Setelah Invoice diterima']);
        PaymentType::create(['name' => '30 days after invoice date', 'description' => '30 Hari Setelah Invoice diterima']);
        PaymentType::create(['name' => '45 Hari', 'description' => '45 Hari Setelah Invoice diterima']);
        PaymentType::create(['name' => '60 Hari', 'description' => '60 Hari Setelah Invoice diterima']);
        PaymentType::create(['name' => 'Cash On Delievery', 'description' => '']);
        PaymentType::create(['name' => '50% DP & 50% After Invoice Received', 'description' => '']);
        PaymentType::create(['name' => '50% DP After Invoice Received', 'description' => '']);
        PaymentType::create(['name' => '50% DP & 50% After Unit Received', 'description' => '']);
        PaymentType::create(['name' => '50% DP, 50% Setelah Pekerjaan Selesai', 'description' => '']);
        PaymentType::create(['name' => '40% saat PO disetujui, 60% setelah report', 'description' => '']);
        PaymentType::create(['name' => '15% Down Payment, 35% Sebelum Training dan Sosiali, 50% Setelah Training dan Sosialisasi selesai (week 8)', 'description' => '']);
        PaymentType::create(['name' => '100% sebelum alat dikirim', 'description' => '']);
    }
}
