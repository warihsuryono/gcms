<?php

namespace App\Filament\Resources\WorkOrderStatusResource\Pages;

use App\Filament\Resources\WorkOrderStatusResource;
use App\Traits\FilamentEditFunctions;
use Filament\Resources\Pages\EditRecord;

class EditWorkOrderStatus extends EditRecord
{
    protected $routename = 'work-order-statuses';
    use FilamentEditFunctions;
    protected static string $resource = WorkOrderStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
