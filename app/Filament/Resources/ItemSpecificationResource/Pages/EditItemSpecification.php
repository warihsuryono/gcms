<?php

namespace App\Filament\Resources\ItemSpecificationResource\Pages;

use App\Filament\Resources\ItemSpecificationResource;
use App\Traits\FilamentEditFunctions;
use Filament\Resources\Pages\EditRecord;

class EditItemSpecification extends EditRecord
{
    protected $routename = 'item-specifications';
    use FilamentEditFunctions;
    protected static string $resource = ItemSpecificationResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
