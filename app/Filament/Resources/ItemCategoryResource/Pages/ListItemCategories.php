<?php

namespace App\Filament\Resources\ItemCategoryResource\Pages;

use App\Filament\Resources\ItemCategoryResource;
use App\Traits\FilamentListFunctions;
use Filament\Resources\Pages\ListRecords;

class ListItemCategories extends ListRecords
{
    use FilamentListFunctions;
    protected static string $resource = ItemCategoryResource::class;
}
