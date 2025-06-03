<?php

namespace App\Filament\Resources\MarriageStatusResource\Pages;

use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\MarriageStatusResource;
use App\Traits\FilamentEditFunctions;

class EditMarriageStatus extends EditRecord
{
    protected $routename = 'marriage-statuses';
    use FilamentEditFunctions;
    protected static string $resource = MarriageStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
