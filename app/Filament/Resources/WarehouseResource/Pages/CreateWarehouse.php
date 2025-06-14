<?php

namespace App\Filament\Resources\WarehouseResource\Pages;

use App\Filament\Resources\WarehouseResource;
use App\Traits\FilamentCreateFunctions;
use Filament\Resources\Pages\CreateRecord;

class CreateWarehouse extends CreateRecord
{
    protected $routename = 'warehouses';
    use FilamentCreateFunctions;
    protected static string $resource = WarehouseResource::class;
    protected static bool $canCreateAnother = false;
    protected function getRedirectUrl(): string
    {
        return route('filament.' . env('PANEL_PATH') . '.resources.warehouses.edit', $this->record->id);
    }
}
