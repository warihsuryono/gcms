<?php

namespace App\Filament\Resources\FieldResource\Pages;

use App\Filament\Resources\FieldResource;
use App\Traits\FilamentEditFunctions;
use Filament\Resources\Pages\EditRecord;

class EditField extends EditRecord
{
    protected $routename = 'fields';
    use FilamentEditFunctions;
    protected static string $resource = FieldResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
