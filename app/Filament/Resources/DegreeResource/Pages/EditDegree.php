<?php

namespace App\Filament\Resources\DegreeResource\Pages;

use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\DegreeResource;
use App\Traits\FilamentEditFunctions;

class EditDegree extends EditRecord
{
    protected $routename = 'degrees';
    use FilamentEditFunctions;
    protected static string $resource = DegreeResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
