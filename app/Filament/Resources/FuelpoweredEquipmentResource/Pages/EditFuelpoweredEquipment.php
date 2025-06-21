<?php

namespace App\Filament\Resources\FuelpoweredEquipmentResource\Pages;

use App\Filament\Resources\FuelpoweredEquipmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFuelpoweredEquipment extends EditRecord
{
    protected static string $resource = FuelpoweredEquipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
