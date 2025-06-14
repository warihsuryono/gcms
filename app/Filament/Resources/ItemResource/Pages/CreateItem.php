<?php

namespace App\Filament\Resources\ItemResource\Pages;

use App\Filament\Resources\ItemResource;
use App\Traits\FilamentCreateFunctions;
use Filament\Resources\Pages\CreateRecord;

class CreateItem extends CreateRecord
{
    protected $routename = 'items';
    use FilamentCreateFunctions;
    protected static string $resource = ItemResource::class;
    protected static bool $canCreateAnother = false;
    protected function getRedirectUrl(): string
    {
        return route('filament.' . env('PANEL_PATH') . '.resources.items.index', $this->record->id);
    }
}
