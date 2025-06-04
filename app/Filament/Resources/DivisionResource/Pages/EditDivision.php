<?php

namespace App\Filament\Resources\DivisionResource\Pages;

use App\Filament\Resources\DivisionResource;
use App\Traits\FilamentEditFunctions;
use Filament\Resources\Pages\EditRecord;

class EditDivision extends EditRecord
{
    protected $routename = 'divisions';
    use FilamentEditFunctions;
    protected static string $resource = DivisionResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
