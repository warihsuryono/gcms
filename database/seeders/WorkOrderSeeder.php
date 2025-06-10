<?php

namespace Database\Seeders;

use App\Models\WorkOrder;
use Illuminate\Database\Seeder;

class WorkOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WorkOrder::create(['work_start' => date("Y-m-d") . ' 02:00:00', 'division_id' => 1, 'field_ids' => '["1","2","4","6"]', 'works' => '
potong semua rumput green ( sibahura .6.unit )
<br>reking bungker ( sempro )
<br>finising pasir di tee box hitam ( sempro )
<br>potong rumput farway ( lf 570 )
<br>cuci alat kalau sudah selesai bekerja 
<br>pupuk semprot semua rumput green 
<br>bahan : ditnahe m 45
<br>alat : SDI
<br>work oder # 095 /2025
']);
    }
}
