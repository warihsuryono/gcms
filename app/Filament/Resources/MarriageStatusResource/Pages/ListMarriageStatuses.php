<?php

namespace App\Filament\Resources\MarriageStatusResource\Pages;

use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\MarriageStatusResource;
use App\Traits\FilamentListFunctions;

class ListMarriageStatuses extends ListRecords
{
    use FilamentListFunctions;
    protected static string $resource = MarriageStatusResource::class;
}
