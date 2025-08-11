<?php

namespace App\Filament\Resources\UrgentWorkOrderResource\Pages;

use App\Filament\Resources\UrgentWorkOrderResource;
use App\Traits\FilamentListFunctions;
use Filament\Resources\Pages\ListRecords;

class ListUrgentWorkOrders extends ListRecords
{
    use FilamentListFunctions;
    protected static string $resource = UrgentWorkOrderResource::class;
}
