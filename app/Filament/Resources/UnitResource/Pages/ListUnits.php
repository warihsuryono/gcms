<?php

namespace App\Filament\Resources\UnitResource\Pages;

use App\Filament\Resources\UnitResource;
use App\Traits\FilamentListFunctions;
use Filament\Resources\Pages\ListRecords;

class ListUnits extends ListRecords
{
    use FilamentListFunctions;
    protected static string $resource = UnitResource::class;
}
