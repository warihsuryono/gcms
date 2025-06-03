<?php

namespace App\Filament\Resources\AttendanceTargetResource\Pages;

use App\Filament\Resources\AttendanceTargetResource;
use App\Traits\FilamentListFunctions;
use Filament\Resources\Pages\ListRecords;

class ListAttendanceTargets extends ListRecords
{
    protected $routename = 'attendance-targets';
    use FilamentListFunctions;
    protected static string $resource = AttendanceTargetResource::class;
}
