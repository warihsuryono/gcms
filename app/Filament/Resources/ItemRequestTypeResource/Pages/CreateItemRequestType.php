<?php

namespace App\Filament\Resources\ItemRequestTypeResource\Pages;

use App\Filament\Resources\ItemRequestTypeResource;
use App\Traits\FilamentCreateFunctions;
use Filament\Resources\Pages\CreateRecord;

class CreateItemRequestType extends CreateRecord
{
    protected $routename = 'item-request-types';
    use FilamentCreateFunctions;
    protected static string $resource = ItemRequestTypeResource::class;
    protected static bool $canCreateAnother = false;
    protected function getRedirectUrl(): string
    {
        return route('filament.' . env('PANEL_PATH') . '.resources.item-request-types.index', $this->record->id);
    }
}
