<?php

namespace App\Filament\Resources\FuelpoweredEquipmentResource\Pages;

use App\Filament\Resources\FuelpoweredEquipmentResource;
use App\Traits\FilamentListFunctions;
use Filament\Resources\Pages\ListRecords;

class ListFuelpoweredEquipment extends ListRecords
{
    use FilamentListFunctions;
    protected static string $resource = FuelpoweredEquipmentResource::class;

    public function getTitle(): string
    {
        return 'Fuel Powered Equipments';
    }
}
