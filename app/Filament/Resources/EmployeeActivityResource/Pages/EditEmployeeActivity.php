<?php

namespace App\Filament\Resources\EmployeeActivityResource\Pages;

use App\Filament\Resources\EmployeeActivityResource;
use App\Traits\FilamentEditFunctions;
use Filament\Resources\Pages\EditRecord;

class EditEmployeeActivity extends EditRecord
{
    protected $routename = 'employee-activities';
    use FilamentEditFunctions;
    protected static string $resource = EmployeeActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
