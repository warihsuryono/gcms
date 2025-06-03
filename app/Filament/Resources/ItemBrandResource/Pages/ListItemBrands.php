<?php

namespace App\Filament\Resources\ItemBrandResource\Pages;

use App\Filament\Resources\ItemBrandResource;
use App\Traits\FilamentListFunctions;
use Filament\Resources\Pages\ListRecords;

class ListItemBrands extends ListRecords
{
    use FilamentListFunctions;
    protected static string $resource = ItemBrandResource::class;
}
