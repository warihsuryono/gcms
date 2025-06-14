<?php

namespace App\Filament\Resources\ItemMovementTypeResource\Pages;

use App\Filament\Resources\ItemMovementTypeResource;
use App\Traits\FilamentEditFunctions;
use Filament\Resources\Pages\EditRecord;

class EditItemMovementType extends EditRecord
{
    protected $routename = 'item-movement-types';
    use FilamentEditFunctions;
    protected static string $resource = ItemMovementTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
