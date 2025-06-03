<?php

namespace App\Filament\Resources\MenuResource\Pages;

use App\Filament\Resources\MenuResource;
use Filament\Resources\Pages\EditRecord;
use App\Traits\FilamentEditFunctions;

class EditMenu extends EditRecord
{
    protected $routename = 'menus';
    use FilamentEditFunctions;
    protected static string $resource = MenuResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
