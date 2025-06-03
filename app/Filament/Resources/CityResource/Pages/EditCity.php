<?php

namespace App\Filament\Resources\CityResource\Pages;

use App\Filament\Resources\CityResource;
use Filament\Resources\Pages\EditRecord;
use App\Traits\FilamentEditFunctions;

class EditCity extends EditRecord
{
    protected $routename = 'cities';
    use FilamentEditFunctions;
    protected static string $resource = CityResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
