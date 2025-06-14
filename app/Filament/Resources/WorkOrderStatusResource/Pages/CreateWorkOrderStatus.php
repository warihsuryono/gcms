<?php

namespace App\Filament\Resources\WorkOrderStatusResource\Pages;

use App\Filament\Resources\WorkOrderStatusResource;
use App\Traits\FilamentCreateFunctions;
use Filament\Resources\Pages\CreateRecord;

class CreateWorkOrderStatus extends CreateRecord
{
    protected $routename = 'work-order-statuses';
    use FilamentCreateFunctions;
    protected static string $resource = WorkOrderStatusResource::class;
    protected static bool $canCreateAnother = false;
    protected function getRedirectUrl(): string
    {
        return route('filament.' . env('PANEL_PATH') . '.resources.work-order-statuses.index', $this->record->id);
    }
}
