<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Sushi\Sushi;

class AttendanceFile extends Model
{
    use Sushi;

    public function getRows(){
        $files  = File::allFiles(storage_path("app/public/attendances"));
        $rows = [];
        foreach ($files as $file) {
            $rows[] = [
                "filename" => $file->getFilename(),
                "size" => $file->getSize(),
                "created_at" => date("Y-m-d H:i:s", $file->getCTime()),
            ];
        }
        return $rows;
    }

    public function getColumns(){
        return [
            "filename",
            "size",
            "created_at",
        ];
    }
}
