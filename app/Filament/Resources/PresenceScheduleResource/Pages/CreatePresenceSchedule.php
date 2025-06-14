<?php

namespace App\Filament\Resources\PresenceScheduleResource\Pages;

use App\Filament\Resources\PresenceScheduleResource;
use App\Traits\FilamentCreateFunctions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreatePresenceSchedule extends CreateRecord
{
    protected $routename = 'presence-schedules';
    use FilamentCreateFunctions;
    protected static string $resource = PresenceScheduleResource::class;
    protected static bool $canCreateAnother = false;
    protected function getRedirectUrl(): string
    {
        return route('filament.' . env('PANEL_PATH') . '.resources.presence-schedules.index', $this->record->id);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (!isset($data['user_id'])) $data['user_id'] = Auth::user()->id;
        return $data;
    }
}
