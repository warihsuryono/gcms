<?php

namespace App\Filament\Resources\ProvinceResource\Pages;

use App\Traits\FilamentEditFunctions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\ProvinceResource;

class EditProvince extends EditRecord
{
    protected $routename = 'provinces';
    use FilamentEditFunctions;
    protected static string $resource = ProvinceResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
