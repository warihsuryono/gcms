<?php

namespace App\Filament\Resources\ItemTypeResource\Pages;

use App\Filament\Resources\ItemTypeResource;
use App\Traits\FilamentEditFunctions;
use Filament\Resources\Pages\EditRecord;

class EditItemType extends EditRecord
{
    protected $routename = 'item-types';
    use FilamentEditFunctions;
    protected static string $resource = ItemTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
