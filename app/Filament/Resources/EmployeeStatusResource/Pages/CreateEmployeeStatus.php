<?php

namespace App\Filament\Resources\EmployeeStatusResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\EmployeeStatusResource;
use App\Traits\FilamentCreateFunctions;

class CreateEmployeeStatus extends CreateRecord
{
    use FilamentCreateFunctions;
    protected static string $resource = EmployeeStatusResource::class;
    protected static bool $canCreateAnother = false;
    protected function getRedirectUrl(): string
    {
        return route('filament.' . env('PANEL_PATH') . '.resources.employee-statuses.index', $this->record->id);
    }
}
