<?php

namespace App\Filament\Resources\FuelConsumptionResource\Pages;

use App\Filament\Resources\FuelConsumptionResource;
use App\Traits\FilamentListFunctions;
use Filament\Resources\Pages\ListRecords;

class ListFuelConsumptions extends ListRecords
{
    use FilamentListFunctions;
    protected static string $resource = FuelConsumptionResource::class;
}
