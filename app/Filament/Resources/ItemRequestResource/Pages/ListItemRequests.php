<?php

namespace App\Filament\Resources\ItemRequestResource\Pages;

use App\Filament\Resources\ItemRequestResource;
use App\Traits\FilamentListFunctions;
use Filament\Resources\Pages\ListRecords;

class ListItemRequests extends ListRecords
{
    use FilamentListFunctions;
    protected static string $resource = ItemRequestResource::class;
}
