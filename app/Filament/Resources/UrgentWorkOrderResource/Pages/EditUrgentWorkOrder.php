<?php

namespace App\Filament\Resources\UrgentWorkOrderResource\Pages;

use App\Filament\Resources\UrgentWorkOrderResource;
use App\Traits\FilamentEditFunctions;
use Filament\Resources\Pages\EditRecord;

class EditUrgentWorkOrder extends EditRecord
{
    protected $routename = 'urgent-work-orders';
    use FilamentEditFunctions;
    protected static string $resource = UrgentWorkOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
