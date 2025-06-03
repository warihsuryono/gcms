<?php

namespace App\Filament\Resources\ItemCategoryResource\Pages;

use App\Filament\Resources\ItemCategoryResource;
use App\Traits\FilamentEditFunctions;
use Filament\Resources\Pages\EditRecord;

class EditItemCategory extends EditRecord
{
    protected $routename = 'item-categories';
    use FilamentEditFunctions;
    protected static string $resource = ItemCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
