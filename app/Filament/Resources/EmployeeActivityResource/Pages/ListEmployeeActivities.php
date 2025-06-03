<?php

namespace App\Filament\Resources\EmployeeActivityResource\Pages;

use App\Filament\Resources\EmployeeActivityResource;
use App\Traits\FilamentListFunctions;
use Filament\Resources\Pages\ListRecords;

class ListEmployeeActivities extends ListRecords
{
    use FilamentListFunctions;
    protected static string $resource = EmployeeActivityResource::class;
}
