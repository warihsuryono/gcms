<?php

namespace Database\Seeders;

use App\Models\LeaveType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $leaves = [
            "Cuti Tahunan",
            "Cuti Tanpa Bayar",
            "Ganti Hari",
            "Izin Datang Terlambat",
            "Izin Pulang Lebih Awal",
            "Sakit",
            "Hamil dan Melahirkan",
            "Berduka Cita Keluarga",
            "Berduka Cita Kerabat Satu Tempat Tinggal",
            "Cuti Menikah",
            "Cuti Sakit Haid",
            "Cuti Penting",
            "Work From Home",
        ];
        foreach ($leaves as $leave) {
            LeaveType::updateOrCreate(["name" => $leave], ["name" => $leave]);
        }
    }
}
