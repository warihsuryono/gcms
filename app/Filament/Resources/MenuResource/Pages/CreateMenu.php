<?php

namespace App\Filament\Resources\MenuResource\Pages;

use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\MenuResource;
use Filament\Resources\Pages\CreateRecord;
use App\Traits\FilamentCreateFunctions;

class CreateMenu extends CreateRecord
{
    protected $routename = 'menus';
    use FilamentCreateFunctions;
    protected static string $resource = MenuResource::class;
    protected static bool $canCreateAnother = false;
    protected function getRedirectUrl(): string
    {
        return route('filament.' . env('PANEL_PATH') . '.resources.menus.index', $this->record->id);
    }

    protected function handleRecordCreation(array $data): Model
    {
        $lastmenu = static::getModel()::where(['parent_id' => '0'])->orderby('seqno', 'desc')->first();
        $data["seqno"] = $lastmenu->seqno + 1;
        return static::getModel()::create($data);
    }
}
