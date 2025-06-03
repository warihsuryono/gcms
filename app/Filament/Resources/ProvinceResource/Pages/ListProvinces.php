<?php

namespace App\Filament\Resources\ProvinceResource\Pages;

use App\Traits\FilamentListFunctions;
use App\Filament\Resources\ProvinceResource;
use Filament\Resources\Pages\ListRecords;


class ListProvinces extends ListRecords
{
    use FilamentListFunctions;
    protected static string $resource = ProvinceResource::class;
}
