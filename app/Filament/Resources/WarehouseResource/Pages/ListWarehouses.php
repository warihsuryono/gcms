<?php

namespace App\Filament\Resources\WarehouseResource\Pages;

use App\Filament\Resources\WarehouseResource;
use App\Traits\FilamentListFunctions;
use Filament\Resources\Pages\ListRecords;

class ListWarehouses extends ListRecords
{
    protected $routename = "warehouses";
    use FilamentListFunctions;
    protected static string $resource = WarehouseResource::class;
}
