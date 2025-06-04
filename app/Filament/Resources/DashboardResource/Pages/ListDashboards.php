<?php

namespace App\Filament\Resources\DashboardResource\Pages;

use App\Filament\Resources\DashboardResource;
use App\Traits\FilamentListFunctions;
use Filament\Resources\Pages\ListRecords;

class ListDashboards extends ListRecords
{
    use FilamentListFunctions;
    protected static string $resource = DashboardResource::class;
}
