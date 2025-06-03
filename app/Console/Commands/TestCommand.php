<?php

namespace App\Console\Commands;

use App\Models\Dispersion;
use Carbon\Carbon;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tapIn = Carbon::parse("2025-04-30 08:00:00");
        $tapOut = Carbon::parse("2025-04-30 16:00:00"); 
        
        $diff_today = strtotime($tapOut) - strtotime($tapIn);
        $remain_today = (8 * 3600) - $diff_today;
        if(date("H") > 13 || true){
            $diff_today = $diff_today - 3600;
            $remain_today = $remain_today + 3600;
        }
                    
        $today_h = intdiv($diff_today, 3600);
        $today_m = intdiv(($diff_today - ($today_h * 3600)), 60);
        $today_s = ($diff_today - ($today_h * 3600)) % 60;
        $today = $today_h . "h " . $today_m . "m " . $today_s . "s ";

        $remain_h = intdiv($remain_today, 3600);
        $remain_m = intdiv(($remain_today - ($remain_h * 3600)), 60);
        $remain_s = ($remain_today - ($remain_h * 3600)) % 60;
        $remain_today = $remain_h . "h " . $remain_m . "m " . $remain_s . "s ";

        $this->info("remain:".$remain_today);
        $this->info("today:".$today);

        // $breakStart = Carbon::parse(date("Y-m-d 12:00:00"));
        // $breakEnd = Carbon::parse(date("Y-m-d 13:00:00"));
        // $day_minutes = abs(strtotime($tapIn) - strtotime($tapOut)) / 60;
        // // tapIn less than equeal $breakStart and tapOut greather than equal $breakEnd
        // if ($tapIn->lte($breakStart) && $tapOut->gte($breakEnd)) {
        //     $day_minutes -= 60;
        // }
        // $this->info($day_minutes);
        // $this->info(intdiv($day_minutes, 60) . " jam " . ($day_minutes % 60) . " menit");
    }
}
