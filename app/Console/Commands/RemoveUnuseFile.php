<?php

namespace App\Console\Commands;

use App\Models\AttendanceFile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RemoveUnuseFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:remove-unuse-file';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove unuse file in storage/app/public/attendances every 1 month to delete 2 month ago file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try{
            $twoMonthAgo = now()->subMonths(2)->format("Y-m-d 00:00:00");
            $this->info("Remove file before: ".$twoMonthAgo);
            $files = AttendanceFile::where("created_at", "<", $twoMonthAgo)->get();
            foreach ($files as $file) {
                $path = storage_path("app/public/attendances/".$file->filename);
                if(file_exists($path)){
                    unlink($path);
                }
                $file->delete();
            }
            if($files->count() > 0){
                $this->info("Success remove ". $files->count() . " file");
            }else{
                $this->info("No file to remove");
            }

        }catch(\Exception $e){
            $this->error($e->getMessage());
            Log::error("Error app:remove-unuse-file: ".$e->getMessage());
        }
    }
}
