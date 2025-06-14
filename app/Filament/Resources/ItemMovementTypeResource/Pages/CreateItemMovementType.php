<?php

namespace App\Filament\Resources\ItemMovementTypeResource\Pages;

use App\Filament\Resources\ItemMovementTypeResource;
use App\Traits\FilamentCreateFunctions;
use Filament\Resources\Pages\CreateRecord;

class CreateItemMovementType extends CreateRecord
{
    protected $routename = 'item-movement-types';
    use FilamentCreateFunctions;
    protected static string $resource = ItemMovementTypeResource::class;
    protected static bool $canCreateAnother = false;
    protected function getRedirectUrl(): string
    {
        return route('filament.' . env('PANEL_PATH') . '.resources.item-movement-types.index', $this->record->id);
    }
}
