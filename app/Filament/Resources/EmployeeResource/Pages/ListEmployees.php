<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\EmployeeResource;
use App\Traits\FilamentListFunctions;

class ListEmployees extends ListRecords
{
    use FilamentListFunctions;
    protected static string $resource = EmployeeResource::class;
}
