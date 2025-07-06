<?php

namespace App\Filament\Resources\FuelConsumptionResource\Pages;

use App\Filament\Resources\FuelConsumptionResource;
// use App\Traits\FilamentListFunctions;
use Filament\Resources\Pages\ListRecords;
use App\Models\menu;
use Filament\Actions\CreateAction;
use App\Http\Controllers\PrivilegeController;
use Filament\Notifications\Notification;

class ListFuelConsumptions extends ListRecords
{
    protected $routename = 'fuel-consumptions';
    protected static string $resource = FuelConsumptionResource::class;
    // use FilamentListFunctions;

    protected function actions(): array
    {
        $routename = $this->routename;
        $menu_ids = menu::where('url', $routename)->get()->pluck('id');
        $actions = [];
        if (PrivilegeController::privilege_check($menu_ids, 1))
            array_push($actions, CreateAction::make()->icon('heroicon-o-plus'));
        return $actions;
    }

    protected function getHeaderActions(): array
    {
        $routename = $this->routename;
        if (!PrivilegeController::privilege_check(menu::where('url', $routename)->get()->pluck('id')) && $routename != 'livewire') {
            Notification::make()->title('Sorry, you don`t have the privilege!')->warning()->send();
            redirect(env('PANEL_PATH'));
        }
        return self::actions();
    }
}
