<?php

namespace App\Filament\Resources\DegreeResource\Pages;

use App\Filament\Resources\DegreeResource;
use Filament\Resources\Pages\CreateRecord;
use App\Traits\FilamentCreateFunctions;

class CreateDegree extends CreateRecord
{
    protected $routename = 'degrees';
    use FilamentCreateFunctions;
    protected static string $resource = DegreeResource::class;
    protected static bool $canCreateAnother = false;
    protected function getRedirectUrl(): string
    {
        return route('filament.' . env('PANEL_PATH') . '.resources.degrees.index', $this->record->id);
    }
}
