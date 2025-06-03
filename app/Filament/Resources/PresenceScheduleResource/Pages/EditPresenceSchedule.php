<?php

namespace App\Filament\Resources\PresenceScheduleResource\Pages;

use App\Filament\Resources\PresenceScheduleResource;
use App\Traits\FilamentEditFunctions;
use Filament\Resources\Pages\EditRecord;

class EditPresenceSchedule extends EditRecord
{
    protected $routename = 'presence-schedules';
    use FilamentEditFunctions;
    protected static string $resource = PresenceScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
