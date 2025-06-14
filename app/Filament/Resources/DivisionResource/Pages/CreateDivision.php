<?php

namespace App\Filament\Resources\DivisionResource\Pages;

use App\Filament\Resources\DivisionResource;
use App\Traits\FilamentCreateFunctions;
use Filament\Resources\Pages\CreateRecord;

class CreateDivision extends CreateRecord
{
    protected $routename = 'divisions';
    use FilamentCreateFunctions;
    protected static string $resource = DivisionResource::class;
    protected static bool $canCreateAnother = false;
    protected function getRedirectUrl(): string
    {
        return route('filament.' . env('PANEL_PATH') . '.resources.divisions.index', $this->record->id);
    }
}
