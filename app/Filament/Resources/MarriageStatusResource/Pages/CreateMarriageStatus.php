<?php

namespace App\Filament\Resources\MarriageStatusResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\MarriageStatusResource;
use App\Traits\FilamentCreateFunctions;

class CreateMarriageStatus extends CreateRecord
{
    protected $routename = 'marriage-statuses';
    use FilamentCreateFunctions;
    protected static string $resource = MarriageStatusResource::class;
    protected static bool $canCreateAnother = false;
    protected function getRedirectUrl(): string
    {
        return route('filament.room.resources.marriage-statuses.index', $this->record->id);
    }
}
