<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\EmployeeResource;
use App\Traits\FilamentEditFunctions;

class EditEmployee extends EditRecord
{
    protected $routename = 'employees';
    use FilamentEditFunctions;
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
