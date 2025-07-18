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
        WorkOrder::create(['work_start' => date("Y-m-d") . ' 02:00:00', 'division_id' => 1, 'field_ids' => '[]', 'works' => '• potong semua rumput green ( Shibaura 6 unit )<br>• pupuk semprot semua rumput green<br>• Finising pasir di tee box hitam<br>• potong rumput Fairway ( lf 570 )<br>• cuci alat kalau sudah selesai bekerja<br>']);
        WorkOrder::create(['work_start' => date("Y-m-d") . ' 05:00:00', 'division_id' => 1, 'field_ids' => '[]', 'works' => '• pupuk semprot semua rumput green<br>• bahan : ditnahe m 45<br>• alat : SDI<br>']);
        WorkOrder::create(['work_start' => date("Y-m-d") . ' 08:00:00', 'division_id' => 1, 'field_ids' => '["1","2","4","6"]', 'works' => '• pupuk rumput tee box<br>• bahan:lokal 10.18.18<br>']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('-1 day')) . ' 02:00:00', 'division_id' => 2, 'field_ids' => '["10"]', 'works' => '• Lanjut bongkar kanstin dan pasang kembali kanstin<br>• di naikan kanstin rata - rata 0,4 cm di pinggir car path di samping Fairway kalo dari tee box sebelah kanan']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('-1 day')) . ' 09:00:00', 'division_id' => 1, 'field_ids' => '[]', 'works' => '• potong semua rumput green + praktis green plus  ( 6 unit Shibaura )<br>• cuci alat kalau sudah selesai bekerja<br>• pupuk semua rumput green<br>• bahan: pk fight + iron mad<br>']);
        WorkOrder::create(['work_start' => date("Y-m-d") . ' 09:00:00', 'division_id' => 1, 'field_ids' => '["3","4","5","6","7","8","9","10"]', 'works' => 'potong  rumput Fairway ( lf 570 + lf 250 )']);
        WorkOrder::create(['work_start' => date("Y-m-d") . ' 07:00:00', 'division_id' => 2, 'field_ids' => '[]', 'works' => 'Lanjut pasang bata merah di gedung training center']);
        WorkOrder::create(['work_start' => date("Y-m-d") . ' 10:00:00', 'division_id' => 1, 'field_ids' => '["9","10","11"]', 'works' => 'potong rumput tee box ( gp 400 + ecplise 322 )']);
        WorkOrder::create(['work_start' => date("Y-m-d") . ' 11:00:00', 'division_id' => 1, 'field_ids' => '["6","7","8","9","10"]', 'works' => 'potong rumput apron ( gp 400 2 unit )']);
        WorkOrder::create(['work_start' => date("Y-m-d") . ' 08:00:00', 'division_id' => 2, 'field_ids' => '[]', 'works' => 'Bongkar jendela musholah di tutup dengan bata merah']);
        WorkOrder::create(['work_start' => date("Y-m-d") . ' 12:00:00', 'division_id' => 1, 'field_ids' => '["5","6","7","8","9","10"]', 'works' => 'potong rumput apron mini ( ecplise 322 + 322 4H )']);
        WorkOrder::create(['work_start' => date("Y-m-d") . ' 13:00:00', 'division_id' => 1, 'field_ids' => '["10","11","12","13","14","15","16","17","18"]', 'works' => 'potong rumput rugh ( AR 522 )']);
        WorkOrder::create(['work_start' => date("Y-m-d") . ' 08:00:00', 'division_id' => 2, 'field_ids' => '[]', 'works' => 'Cor kolom dan latai di gedung training center']);
        WorkOrder::create(['work_start' => date("Y-m-d") . ' 14:00:00', 'division_id' => 1, 'field_ids' => '["11","12","13","14","15","16","17"]', 'works' => 'Raking bungker ( sempro )']);
        WorkOrder::create(['work_start' => date("Y-m-d") . ' 15:00:00', 'division_id' => 1, 'field_ids' => '[]', 'works' => 'blower kelipingan rumput yang sudah selesai dipotong']);
        WorkOrder::create(['work_start' => date("Y-m-d") . ' 16:00:00', 'division_id' => 1, 'field_ids' => '["10"]', 'works' => 'potong rumput bungker ']);

        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('-1 day')) . ' 09:00:00', 'division_id' => 1, 'field_ids' => '["3","4","5","6","7","8","9","10"]', 'works' => 'potong  rumput Fairway ( lf 570 + lf 250 )']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('-2 day')) . ' 07:00:00', 'division_id' => 2, 'field_ids' => '[]', 'works' => 'Lanjut pasang bata merah di gedung training center']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('-2 day')) . ' 10:00:00', 'division_id' => 1, 'field_ids' => '["9","10","11"]', 'works' => 'potong rumput tee box ( gp 400 + ecplise 322 )']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('-3 day')) . ' 11:00:00', 'division_id' => 1, 'field_ids' => '["6","7","8","9","10"]', 'works' => 'potong rumput apron ( gp 400 2 unit )']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('-3 day')) . ' 08:00:00', 'division_id' => 2, 'field_ids' => '[]', 'works' => 'Bongkar jendela musholah di tutup pake bata merah']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('-3 day')) . ' 12:00:00', 'division_id' => 1, 'field_ids' => '["5","6","7","8","9","10"]', 'works' => 'potong rumput apron mini ( ecplise 322 + 322 4H )']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('-3 day')) . ' 13:00:00', 'division_id' => 1, 'field_ids' => '["10","11","12","13","14","15","16","17","18"]', 'works' => 'potong rumput rugh ( AR 522 )']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('-3 day')) . ' 08:00:00', 'division_id' => 2, 'field_ids' => '[]', 'works' => 'Cor kolom dan latai di gedung training center']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('-4 day')) . ' 14:00:00', 'division_id' => 1, 'field_ids' => '["11","12","13","14","15","16","17"]', 'works' => 'Raking bungker ( sempro )']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('-4 day')) . ' 15:00:00', 'division_id' => 1, 'field_ids' => '[]', 'works' => 'blower kelipingan rumput yang sudah selesai dipotong']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('-5 day')) . ' 16:00:00', 'division_id' => 1, 'field_ids' => '["10"]', 'works' => 'potong rumput bungker']);


        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('+1 day')) . ' 09:00:00', 'division_id' => 1, 'field_ids' => '["3","4","5","6","7","8","9","10"]', 'works' => 'potong  rumput Fairway ( lf 570 + lf 250 )']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('+1 day')) . ' 07:00:00', 'division_id' => 2, 'field_ids' => '[]', 'works' => 'Lanjut pasang bata merah di gedung training center']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('+1 day')) . ' 10:00:00', 'division_id' => 1, 'field_ids' => '["9","10","11"]', 'works' => 'potong rumput tee box ( gp 400 + ecplise 322 )']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('+2 day')) . ' 11:00:00', 'division_id' => 1, 'field_ids' => '["6","7","8","9","10"]', 'works' => 'potong rumput apron ( gp 400 2 unit )']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('+2 day')) . ' 08:00:00', 'division_id' => 2, 'field_ids' => '[]', 'works' => 'Bongkar jendela musholah di tutup pake bata merah']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('+2 day')) . ' 12:00:00', 'division_id' => 1, 'field_ids' => '["5","6","7","8","9","10"]', 'works' => 'potong rumput apron mini ( ecplise 322 + 322 4H )']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('+3 day')) . ' 13:00:00', 'division_id' => 1, 'field_ids' => '["10","11","12","13","14","15","16","17","18"]', 'works' => 'potong rumput rugh ( AR 522 )']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('+3 day')) . ' 08:00:00', 'division_id' => 2, 'field_ids' => '[]', 'works' => 'Cor kolom dan latai di gedung training center']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('+3 day')) . ' 14:00:00', 'division_id' => 1, 'field_ids' => '["11","12","13","14","15","16","17"]', 'works' => 'Raking bungker ( sempro )']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('+4 day')) . ' 15:00:00', 'division_id' => 1, 'field_ids' => '[]', 'works' => 'blower kelipingan rumput yang sudah selesai dipotong']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('+4 day')) . ' 16:00:00', 'division_id' => 1, 'field_ids' => '["10"]', 'works' => 'potong rumput bungker']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('+4 day')) . ' 09:00:00', 'division_id' => 1, 'field_ids' => '["3","4","5","6","7","8","9","10"]', 'works' => 'potong  rumput Fairway ( lf 570 + lf 250 )']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('+5 day')) . ' 07:00:00', 'division_id' => 2, 'field_ids' => '[]', 'works' => 'Lanjut pasang bata merah di gedung training center']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('+5 day')) . ' 10:00:00', 'division_id' => 1, 'field_ids' => '["9","10","11"]', 'works' => 'potong rumput tee box ( gp 400 + ecplise 322 )']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('+5 day')) . ' 11:00:00', 'division_id' => 1, 'field_ids' => '["6","7","8","9","10"]', 'works' => 'potong rumput apron ( gp 400 2 unit )']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('+4 day')) . ' 08:00:00', 'division_id' => 2, 'field_ids' => '[]', 'works' => 'Bongkar jendela musholah di tutup pake bata merah']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('+3 day')) . ' 12:00:00', 'division_id' => 1, 'field_ids' => '["5","6","7","8","9","10"]', 'works' => 'potong rumput apron mini ( ecplise 322 + 322 4H )']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('+2 day')) . ' 13:00:00', 'division_id' => 1, 'field_ids' => '["10","11","12","13","14","15","16","17","18"]', 'works' => 'potong rumput rugh ( AR 522 )']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('+2 day')) . ' 08:00:00', 'division_id' => 2, 'field_ids' => '[]', 'works' => 'Cor kolom dan latai di gedung training center']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('+1 day')) . ' 14:00:00', 'division_id' => 1, 'field_ids' => '["11","12","13","14","15","16","17"]', 'works' => 'Raking bungker ( sempro )']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('+2 day')) . ' 15:00:00', 'division_id' => 1, 'field_ids' => '[]', 'works' => 'blower kelipingan rumput yang sudah selesai dipotong']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('+3 day')) . ' 16:00:00', 'division_id' => 1, 'field_ids' => '["10"]', 'works' => 'potong rumput bungker']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('+4 day')) . ' 09:00:00', 'division_id' => 1, 'field_ids' => '["3","4","5","6","7","8","9","10"]', 'works' => 'potong  rumput Fairway ( lf 570 + lf 250 )']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('+5 day')) . ' 07:00:00', 'division_id' => 2, 'field_ids' => '[]', 'works' => 'Lanjut pasang bata merah di gedung training center']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('+6 day')) . ' 10:00:00', 'division_id' => 1, 'field_ids' => '["9","10","11"]', 'works' => 'potong rumput tee box ( gp 400 + ecplise 322 )']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('+7 day')) . ' 11:00:00', 'division_id' => 1, 'field_ids' => '["6","7","8","9","10"]', 'works' => 'potong rumput apron ( gp 400 2 unit )']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('+5 day')) . ' 08:00:00', 'division_id' => 2, 'field_ids' => '[]', 'works' => 'Bongkar jendela musholah di tutup pake bata merah']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('+6 day')) . ' 12:00:00', 'division_id' => 1, 'field_ids' => '["5","6","7","8","9","10"]', 'works' => 'potong rumput apron mini ( ecplise 322 + 322 4H )']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('+3 day')) . ' 13:00:00', 'division_id' => 1, 'field_ids' => '["10","11","12","13","14","15","16","17","18"]', 'works' => 'potong rumput rugh ( AR 522 )']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('+5 day')) . ' 08:00:00', 'division_id' => 2, 'field_ids' => '[]', 'works' => 'Cor kolom dan latai di gedung training center']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('+6 day')) . ' 14:00:00', 'division_id' => 1, 'field_ids' => '["11","12","13","14","15","16","17"]', 'works' => 'Raking bungker ( sempro )']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('+7 day')) . ' 15:00:00', 'division_id' => 1, 'field_ids' => '[]', 'works' => 'blower kelipingan rumput yang sudah selesai dipotong']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('+5 day')) . ' 16:00:00', 'division_id' => 1, 'field_ids' => '["10"]', 'works' => 'potong rumput bungker']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('+4 day')) . ' 09:00:00', 'division_id' => 1, 'field_ids' => '["3","4","5","6","7","8","9","10"]', 'works' => 'potong  rumput Fairway ( lf 570 + lf 250 )']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('+3 day')) . ' 07:00:00', 'division_id' => 2, 'field_ids' => '[]', 'works' => 'Lanjut pasang bata merah di gedung training center']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('+2 day')) . ' 10:00:00', 'division_id' => 1, 'field_ids' => '["9","10","11"]', 'works' => 'potong rumput tee box ( gp 400 + ecplise 322 )']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('+2 day')) . ' 11:00:00', 'division_id' => 1, 'field_ids' => '["6","7","8","9","10"]', 'works' => 'potong rumput apron ( gp 400 2 unit )']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('+1 day')) . ' 08:00:00', 'division_id' => 2, 'field_ids' => '[]', 'works' => 'Bongkar jendela musholah di tutup pake bata merah']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('+2 day')) . ' 12:00:00', 'division_id' => 1, 'field_ids' => '["5","6","7","8","9","10"]', 'works' => 'potong rumput apron mini ( ecplise 322 + 322 4H )']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('+3 day')) . ' 13:00:00', 'division_id' => 1, 'field_ids' => '["10","11","12","13","14","15","16","17","18"]', 'works' => 'potong rumput rugh ( AR 522 )']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('+4 day')) . ' 08:00:00', 'division_id' => 2, 'field_ids' => '[]', 'works' => 'Cor kolom dan latai di gedung training center']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('+3 day')) . ' 14:00:00', 'division_id' => 1, 'field_ids' => '["11","12","13","14","15","16","17"]', 'works' => 'Raking bungker ( sempro )']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('+2 day')) . ' 15:00:00', 'division_id' => 1, 'field_ids' => '[]', 'works' => 'blower kelipingan rumput yang sudah selesai dipotong']);
        WorkOrder::create(['work_start' => date("Y-m-d", strtotime('+7 day')) . ' 16:00:00', 'division_id' => 1, 'field_ids' => '["10"]', 'works' => 'potong rumput bungker']);
    }
}
