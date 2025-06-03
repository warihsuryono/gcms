<?php

namespace App\Traits;

use App\Models\menu;
use Filament\Actions\CreateAction;
use Illuminate\Support\Facades\Route;
use Filament\Notifications\Notification;
use App\Http\Controllers\PrivilegeController;

trait FilamentListFunctions
{
    protected function actions(): array
    {
        $routename = (isset($this->routename)) ? $this->routename : explode('/', str_replace(env('PANEL_PATH') . '/', '', Route::current()->uri))[0];
        $menu_ids = menu::where('url', $routename)->get()->pluck('id');
        $actions = [];
        if (PrivilegeController::privilege_check($menu_ids, 1))
            array_push($actions, CreateAction::make()->icon('heroicon-o-plus'));
        return $actions;
    }

    protected function getHeaderActions(): array
    {
        $routename = (isset($this->routename)) ? $this->routename : explode('/', str_replace(env('PANEL_PATH') . '/', '', Route::current()->uri))[0];
        if (!PrivilegeController::privilege_check(menu::where('url', $routename)->get()->pluck('id')) && $routename != 'livewire') {
            Notification::make()->title('Sorry, you don`t have the privilege!')->warning()->send();
            redirect(env('PANEL_PATH'));
        }
        return self::actions();
    }
}
