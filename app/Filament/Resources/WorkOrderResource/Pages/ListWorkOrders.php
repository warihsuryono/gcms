<?php

namespace App\Filament\Resources\WorkOrderResource\Pages;

use App\Filament\Resources\WorkOrderResource;
use App\Traits\FilamentListFunctions;
use Filament\Resources\Pages\ListRecords;

class ListWorkOrders extends ListRecords
{
    use FilamentListFunctions;
    protected static string $resource = WorkOrderResource::class;
}
