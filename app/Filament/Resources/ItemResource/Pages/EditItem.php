<?php

namespace App\Filament\Resources\ItemResource\Pages;

use App\Filament\Resources\ItemResource;
use App\Traits\FilamentEditFunctions;
use Filament\Resources\Pages\EditRecord;

class EditItem extends EditRecord
{
    protected $routename = 'items';
    use FilamentEditFunctions;
    protected static string $resource = ItemResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
