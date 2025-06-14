<?php

namespace App\Filament\Resources\ItemMovementTypeResource\Pages;

use App\Filament\Resources\ItemMovementTypeResource;
use App\Traits\FilamentListFunctions;
use Filament\Resources\Pages\ListRecords;

class ListItemMovementTypes extends ListRecords
{
    use FilamentListFunctions;
    protected static string $resource = ItemMovementTypeResource::class;
}
