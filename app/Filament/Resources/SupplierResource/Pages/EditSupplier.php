<?php

namespace App\Filament\Resources\SupplierResource\Pages;

use App\Filament\Resources\SupplierResource;
use App\Traits\FilamentEditFunctions;
use Filament\Resources\Pages\EditRecord;

class EditSupplier extends EditRecord
{
    use FilamentEditFunctions;
    protected static string $resource = SupplierResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
