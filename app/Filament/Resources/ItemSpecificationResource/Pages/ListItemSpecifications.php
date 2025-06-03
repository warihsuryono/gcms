<?php

namespace App\Filament\Resources\ItemSpecificationResource\Pages;

use App\Filament\Resources\ItemSpecificationResource;
use App\Traits\FilamentListFunctions;
use Filament\Resources\Pages\ListRecords;

class ListItemSpecifications extends ListRecords
{
    use FilamentListFunctions;
    protected static string $resource = ItemSpecificationResource::class;
}
