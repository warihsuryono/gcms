<?php

namespace App\Filament\Resources\CityResource\Pages;

use App\Filament\Resources\CityResource;
use Filament\Resources\Pages\ListRecords;
use App\Traits\FilamentListFunctions;

class ListCities extends ListRecords
{
    use FilamentListFunctions;
    protected static string $resource = CityResource::class;
}
