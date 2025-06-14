<?php

namespace App\Filament\Resources\ItemCategoryResource\Pages;

use App\Filament\Resources\ItemCategoryResource;
use App\Traits\FilamentCreateFunctions;
use Filament\Resources\Pages\CreateRecord;

class CreateItemCategory extends CreateRecord
{
    protected $routename = 'item-categories';
    use FilamentCreateFunctions;
    protected static string $resource = ItemCategoryResource::class;
    protected static bool $canCreateAnother = false;
    protected function getRedirectUrl(): string
    {
        return route('filament.' . env('PANEL_PATH') . '.resources.item-categories.index', $this->record->id);
    }
}
