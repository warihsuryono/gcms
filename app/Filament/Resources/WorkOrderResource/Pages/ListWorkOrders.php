<?php

namespace App\Filament\Resources\WorkOrderResource\Pages;

use App\Filament\Resources\WorkOrderResource;
// use App\Traits\FilamentListFunctions;
use Illuminate\Support\Facades\Route;
use App\Models\menu;
use Filament\Actions\CreateAction;
use App\Http\Controllers\PrivilegeController;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListWorkOrders extends ListRecords
{
    protected $routename = 'work-orders';
    // use FilamentListFunctions;
    protected static string $resource = WorkOrderResource::class;


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
