<?php

namespace App\Filament\Resources\ItemTypeResource\Pages;

use App\Filament\Resources\ItemTypeResource;
use App\Traits\FilamentCreateFunctions;
use Filament\Resources\Pages\CreateRecord;

class CreateItemType extends CreateRecord
{
    protected $routename = 'item-types';
    use FilamentCreateFunctions;
    protected static string $resource = ItemTypeResource::class;
    protected static bool $canCreateAnother = false;
    protected function getRedirectUrl(): string
    {
        return route('filament.' . env('PANEL_PATH') . '.resources.item-types.index', $this->record->id);
    }
}
