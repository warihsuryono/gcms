<?php

namespace App\Filament\Resources\EmployeeStatusResource\Pages;

use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\EmployeeStatusResource;
use App\Traits\FilamentListFunctions;

class ListEmployeeStatuses extends ListRecords
{
    use FilamentListFunctions;
    protected static string $resource = EmployeeStatusResource::class;
}
