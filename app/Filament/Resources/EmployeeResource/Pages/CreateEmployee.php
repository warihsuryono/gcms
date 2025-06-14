<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\EmployeeResource;
use App\Traits\FilamentCreateFunctions;

class CreateEmployee extends CreateRecord
{
    protected $routename = 'employees';
    use FilamentCreateFunctions;
    protected static string $resource = EmployeeResource::class;
    protected static bool $canCreateAnother = false;
    protected function getRedirectUrl(): string
    {
        return route('filament.' . env('PANEL_PATH') . '.resources.employees.index', $this->record->id);
    }
}
