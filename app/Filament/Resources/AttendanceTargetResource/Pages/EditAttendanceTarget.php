<?php

namespace App\Filament\Resources\AttendanceTargetResource\Pages;

use App\Filament\Resources\AttendanceTargetResource;
use App\Traits\FilamentEditFunctions;
use Filament\Resources\Pages\EditRecord;

class EditAttendanceTarget extends EditRecord
{
    protected $routename = 'attendance-targets';
    use FilamentEditFunctions;
    protected static string $resource = AttendanceTargetResource::class;
    protected function getHeaderActions(): array
    {
        return [];
    }
}
