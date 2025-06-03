<?php

namespace App\Filament\Resources\ProvinceResource\Pages;

use App\Traits\FilamentCreateFunctions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ProvinceResource;

class CreateProvince extends CreateRecord
{
    protected $routename = 'provinces';
    use FilamentCreateFunctions;
    protected static string $resource = ProvinceResource::class;
    protected static bool $canCreateAnother = false;
    protected function getRedirectUrl(): string
    {
        return route('filament.room.resources.provinces.index', $this->record->id);
    }
}
