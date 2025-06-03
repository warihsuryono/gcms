<?php

namespace App\Filament\Resources\ItemTypeResource\Pages;

use App\Filament\Resources\ItemTypeResource;
use App\Traits\FilamentListFunctions;
use Filament\Resources\Pages\ListRecords;

class ListItemTypes extends ListRecords
{
    use FilamentListFunctions;
    protected static string $resource = ItemTypeResource::class;
}
