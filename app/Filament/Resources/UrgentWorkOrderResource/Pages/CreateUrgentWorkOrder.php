<?php

namespace App\Filament\Resources\UrgentWorkOrderResource\Pages;

use App\Filament\Resources\UrgentWorkOrderResource;
use App\Traits\FilamentCreateFunctions;
use Filament\Resources\Pages\CreateRecord;

class CreateUrgentWorkOrder extends CreateRecord
{
    protected $routename = 'urgent-work-orders';
    use FilamentCreateFunctions;
    protected static string $resource = UrgentWorkOrderResource::class;

    protected function getRedirectUrl(): string
    {
        return route('filament.' . env('PANEL_PATH') . '.resources.urgent-work-orders.index', $this->record->id);
    }
}
