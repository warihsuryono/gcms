<?php

namespace App\Filament\Resources\CityResource\Pages;

use App\Filament\Resources\CityResource;
use Filament\Resources\Pages\CreateRecord;
use App\Traits\FilamentCreateFunctions;

class CreateCity extends CreateRecord
{
    protected $routename = 'cities';
    use FilamentCreateFunctions;
    protected static string $resource = CityResource::class;
    protected static bool $canCreateAnother = false;
    protected function getRedirectUrl(): string
    {
        return route('filament.' . env('PANEL_PATH') . '.resources.cities.index', $this->record->id);
    }
}
