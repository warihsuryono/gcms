<?php

namespace App\Filament\Resources\ItemRequestTypeResource\Pages;

use App\Filament\Resources\ItemRequestTypeResource;
use App\Traits\FilamentListFunctions;
use Filament\Resources\Pages\ListRecords;

class ListItemRequestTypes extends ListRecords
{
    use FilamentListFunctions;
    protected static string $resource = ItemRequestTypeResource::class;
}
