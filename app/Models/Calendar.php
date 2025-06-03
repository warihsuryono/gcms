<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    protected $table = 'calendars';

    public function calendar_type(){
        return $this->belongsTo(CalendarType::class);
    }
}
