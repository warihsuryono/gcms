<?php

namespace App\Filament\Resources\FieldResource\Pages;

use App\Filament\Resources\FieldResource;
use App\Traits\FilamentListFunctions;
use Filament\Resources\Pages\ListRecords;

class ListFields extends ListRecords
{
    use FilamentListFunctions;
    protected static string $resource = FieldResource::class;
}
