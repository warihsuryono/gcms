<?php

namespace App\Filament\Resources\FuelConsumptionResource\Pages;

use App\Filament\Resources\FuelConsumptionResource;
use Filament\Resources\Pages\Page;

class ChartFuelConsumptions extends Page
{
    protected static string $resource = FuelConsumptionResource::class;
    protected ?string $heading = 'Fuel Consumptions Chart';
    protected static string $view = 'filament.resources.fuel-consumption-resource.pages.chart-fuel-consumptions';

    public function getBreadcrumbs(): array
    {
        return [
            // route('filament.' . env('PANEL_PATH') . '.resources.fuel-consumptions.report') => 'Fuel Consumptions Report',
            // 'Chart'
        ];
    }
}
