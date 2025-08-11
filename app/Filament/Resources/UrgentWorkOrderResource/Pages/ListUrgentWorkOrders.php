<?php

namespace App\Filament\Resources\UrgentWorkOrderResource\Pages;

use App\Filament\Resources\UrgentWorkOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUrgentWorkOrders extends ListRecords
{
    protected static string $resource = UrgentWorkOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
