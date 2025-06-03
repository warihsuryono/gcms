<?php

namespace App\Filament\Resources\SupplierResource\Pages;

use App\Filament\Resources\SupplierResource;
use App\Traits\FilamentListFunctions;
use Filament\Resources\Pages\ListRecords;

class ListSuppliers extends ListRecords
{
    use FilamentListFunctions;
    protected static string $resource = SupplierResource::class;
}
