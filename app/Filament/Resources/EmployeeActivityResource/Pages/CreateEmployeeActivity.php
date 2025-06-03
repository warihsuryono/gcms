<?php

namespace App\Filament\Resources\EmployeeActivityResource\Pages;

use App\Filament\Resources\EmployeeActivityResource;
use App\Traits\FilamentCreateFunctions;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployeeActivity extends CreateRecord
{
    protected $routename = 'employee-activities';
    use FilamentCreateFunctions;
    protected static string $resource = EmployeeActivityResource::class;
    protected static bool $canCreateAnother = false;
    protected function getRedirectUrl(): string
    {
        return route('filament.room.resources.employee-activities.index', $this->record->id);
    }
}
