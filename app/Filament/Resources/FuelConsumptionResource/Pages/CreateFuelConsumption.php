<?php

namespace App\Filament\Resources\FuelConsumptionResource\Pages;

use App\Filament\Resources\FuelConsumptionResource;
use App\Traits\FilamentCreateFunctions;
use Filament\Resources\Pages\CreateRecord;

class CreateFuelConsumption extends CreateRecord
{
    protected $routename = 'fuel-consumptions';
    use FilamentCreateFunctions;
    protected static string $resource = FuelConsumptionResource::class;

    protected function getRedirectUrl(): string
    {
        return route('filament.' . env('PANEL_PATH') . '.resources.fuel-consumptions.index', $this->record->id);
    }
}
