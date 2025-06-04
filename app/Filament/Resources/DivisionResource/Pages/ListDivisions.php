<?php

namespace App\Filament\Resources\DivisionResource\Pages;

use App\Filament\Resources\DivisionResource;
use App\Traits\FilamentListFunctions;
use Filament\Resources\Pages\ListRecords;

class ListDivisions extends ListRecords
{
    use FilamentListFunctions;
    protected static string $resource = DivisionResource::class;
}
