<?php

namespace App\Filament\Resources\WarehouseResource\Pages;

use App\Filament\Resources\WarehouseResource;
use App\Traits\FilamentEditFunctions;
use Filament\Resources\Pages\EditRecord;

class EditWarehouse extends EditRecord
{
    protected $routename = 'warehouses';
    use FilamentEditFunctions;
    protected static string $resource = WarehouseResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
