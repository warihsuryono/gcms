<?php

namespace App\Filament\Resources\ProfileResource\Pages;

use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ProfileResource;
use App\Traits\FilamentListFunctions;

class ListProfiles extends ListRecords
{
    use FilamentListFunctions;
    protected static string $resource = ProfileResource::class;
}
