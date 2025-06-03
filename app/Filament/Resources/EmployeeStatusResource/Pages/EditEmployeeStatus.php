<?php

namespace App\Filament\Resources\EmployeeStatusResource\Pages;

use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\EmployeeStatusResource;
use App\Traits\FilamentEditFunctions;

class EditEmployeeStatus extends EditRecord
{
    use FilamentEditFunctions;
    protected static string $resource = EmployeeStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
