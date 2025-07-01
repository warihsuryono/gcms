<?php

namespace App\Filament\Resources\StockOpnameResource\Pages;

use App\Filament\Resources\StockOpnameResource;
use App\Traits\FilamentCreateFunctions;
use Filament\Resources\Pages\CreateRecord;

class CreateStockOpname extends CreateRecord
{
    use FilamentCreateFunctions;
    protected static string $resource = StockOpnameResource::class;
    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return route('filament.' . env('PANEL_PATH') . '.resources.stock-opnames.edit', $this->record->id);
    }
}
