<?php

namespace App\Filament\Resources\UnitResource\Pages;

use App\Filament\Resources\UnitResource;
use App\Traits\FilamentEditFunctions;
use Filament\Resources\Pages\EditRecord;

class EditUnit extends EditRecord
{
    protected $routename = 'units';
    use FilamentEditFunctions;
    protected static string $resource = UnitResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
