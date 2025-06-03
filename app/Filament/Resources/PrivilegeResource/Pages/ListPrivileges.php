<?php

namespace App\Filament\Resources\PrivilegeResource\Pages;

use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\PrivilegeResource;
use App\Traits\FilamentListFunctions;

class ListPrivileges extends ListRecords
{
    use FilamentListFunctions;
    protected static string $resource = PrivilegeResource::class;
}
