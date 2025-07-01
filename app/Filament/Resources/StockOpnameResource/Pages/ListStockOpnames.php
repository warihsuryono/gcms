<?php

namespace App\Filament\Resources\StockOpnameResource\Pages;

use App\Filament\Resources\StockOpnameResource;
use App\Traits\FilamentListFunctions;
use Filament\Resources\Pages\ListRecords;

class ListStockOpnames extends ListRecords
{
    use FilamentListFunctions;
    protected static string $resource = StockOpnameResource::class;
}
