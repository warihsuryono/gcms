<?php

namespace App\Filament\Resources\ItemSpecificationResource\Pages;

use App\Filament\Resources\ItemSpecificationResource;
use App\Traits\FilamentCreateFunctions;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateItemSpecification extends CreateRecord
{
    protected $routename = 'item-specifications';
    use FilamentCreateFunctions;
    protected static string $resource = ItemSpecificationResource::class;
    protected static bool $canCreateAnother = false;
    protected function getRedirectUrl(): string
    {
        return route('filament.' . env('PANEL_PATH') . '.resources.item-specifications.index', $this->record->id);
    }
}
