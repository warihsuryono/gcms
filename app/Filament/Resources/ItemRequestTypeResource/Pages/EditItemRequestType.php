<?php

namespace App\Filament\Resources\ItemRequestTypeResource\Pages;

use App\Filament\Resources\ItemRequestTypeResource;
use App\Traits\FilamentEditFunctions;
use Filament\Resources\Pages\EditRecord;

class EditItemRequestType extends EditRecord
{
    protected $routename = 'item-request-types';
    use FilamentEditFunctions;
    protected static string $resource = ItemRequestTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
