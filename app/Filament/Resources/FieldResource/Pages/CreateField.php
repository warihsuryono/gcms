<?php

namespace App\Filament\Resources\FieldResource\Pages;

use App\Filament\Resources\FieldResource;
use App\Traits\FilamentCreateFunctions;
use Filament\Resources\Pages\CreateRecord;

class CreateField extends CreateRecord
{
    protected $routename = 'fields';
    use FilamentCreateFunctions;
    protected static string $resource = FieldResource::class;
    protected static bool $canCreateAnother = false;
    protected function getRedirectUrl(): string
    {
        return route('filament.' . env('PANEL_PATH') . '.resources.fields.index', $this->record->id);
    }
}
