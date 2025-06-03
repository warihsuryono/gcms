<?php

namespace App\Filament\Resources\ChildmenuResource\Pages;

use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\ChildmenuResource;
use App\Traits\FilamentEditFunctions;

class EditChildmenu extends EditRecord
{
    protected $routename = 'menus';
    use FilamentEditFunctions;
    protected static string $resource = ChildmenuResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
