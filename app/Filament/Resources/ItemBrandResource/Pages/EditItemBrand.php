<?php

namespace App\Filament\Resources\ItemBrandResource\Pages;

use App\Filament\Resources\ItemBrandResource;
use App\Traits\FilamentEditFunctions;
use Filament\Resources\Pages\EditRecord;

class EditItemBrand extends EditRecord
{
    protected $routename = 'item-brands';
    use FilamentEditFunctions;
    protected static string $resource = ItemBrandResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
