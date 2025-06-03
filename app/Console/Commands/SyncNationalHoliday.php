<?php

namespace App\Console\Commands;

use App\Models\Calendar;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SyncNationalHoliday extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-national-holiday';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync national holiday from API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try{
            $year = now()->year;
            $apiKey = env('GOOGLE_CALENDAR_API_KEY');
            $request = Http::get("https://www.googleapis.com/calendar/v3/calendars/id.indonesian%23holiday%40group.v.calendar.google.com/events?key={$apiKey}&timeMin={$year}-01-01T00:00:00Z");
            if($request->failed()){
                throw new \Exception('Failed to fetch data from API');
            }
            $data = $request->object();
            $events = $data->items;
            foreach ($events as $event) {
                if(str_contains(strtolower($event->summary), 'cuti bersama')){
                    $calendarTypeId = 2;
                }elseif(str_contains(strtolower($event->description), 'libur nasional')){
                    $calendarTypeId = 1;
                }elseif(str_contains(strtolower($event->description), 'perayaan')){
                    $calendarTypeId = 6;
                }else{
                    $calendarTypeId = 6;
                }
                Calendar::updateOrCreate([
                    'name' => $event->summary,
                    'start_date' => $event->start->date,
                    'end_date' => $event->end->date,
                ],[
                    'calendar_type_id' => $calendarTypeId,
                    'name' => $event->summary,
                    'description' => $event->description,
                    'start_date' => $event->start->date,
                    'end_date' => $event->end->date,
                ]);
            }
            $this->info("National holiday {$year} has been synced :".count($events));
        }catch(\Exception $e){
            $this->error($e->getMessage());
        }
    }
}
