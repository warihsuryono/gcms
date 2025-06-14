<?php

namespace App\Filament\Resources\AttendanceTargetResource\Pages;

use App\Filament\Resources\AttendanceTargetResource;
use App\Traits\FilamentCreateFunctions;
use Filament\Resources\Pages\CreateRecord;

class CreateAttendanceTarget extends CreateRecord
{
    protected $routename = 'attendance-targets';
    use FilamentCreateFunctions;
    protected static string $resource = AttendanceTargetResource::class;
    protected static bool $canCreateAnother = false;
    protected function getRedirectUrl(): string
    {
        return route('filament.' . env('PANEL_PATH') . '.resources.attendance-targets.index', $this->record->id);
    }
}
