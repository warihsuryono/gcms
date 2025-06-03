<?php

namespace App\Filament\Resources\DegreeResource\Pages;

use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\DegreeResource;
use App\Traits\FilamentListFunctions;

class ListDegrees extends ListRecords
{
    use FilamentListFunctions;
    protected static string $resource = DegreeResource::class;
}
