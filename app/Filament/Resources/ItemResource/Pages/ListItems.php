<?php

namespace App\Filament\Resources\ItemResource\Pages;

use App\Filament\Resources\ItemResource;
use App\Traits\FilamentListFunctions;
use Filament\Resources\Pages\ListRecords;

class ListItems extends ListRecords
{
    use FilamentListFunctions;
    protected static string $resource = ItemResource::class;
}
