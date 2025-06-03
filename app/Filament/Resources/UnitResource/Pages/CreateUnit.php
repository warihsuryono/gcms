<?php

namespace App\Filament\Resources\UnitResource\Pages;

use App\Filament\Resources\UnitResource;
use App\Traits\FilamentCreateFunctions;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUnit extends CreateRecord
{
    protected $routename = 'units';
    use FilamentCreateFunctions;
    protected static string $resource = UnitResource::class;
    protected static bool $canCreateAnother = false;
    protected function getRedirectUrl(): string
    {
        return route('filament.room.resources.units.index', $this->record->id);
    }
}
