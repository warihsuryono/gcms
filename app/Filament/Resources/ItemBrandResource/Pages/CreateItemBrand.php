<?php

namespace App\Filament\Resources\ItemBrandResource\Pages;

use App\Filament\Resources\ItemBrandResource;
use App\Traits\FilamentCreateFunctions;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateItemBrand extends CreateRecord
{
    protected $routename = 'item-brands';
    use FilamentCreateFunctions;
    protected static string $resource = ItemBrandResource::class;
    protected static bool $canCreateAnother = false;
    protected function getRedirectUrl(): string
    {
        return route('filament.' . env('PANEL_PATH') . '.resources.item-brands.index', $this->record->id);
    }
}
