<?php

namespace App\Filament\Resources\WorkOrderStatusResource\Pages;

use App\Filament\Resources\WorkOrderStatusResource;
use App\Traits\FilamentListFunctions;
use Filament\Resources\Pages\ListRecords;

class ListWorkOrderStatuses extends ListRecords
{
    use FilamentListFunctions;
    protected static string $resource = WorkOrderStatusResource::class;
}
