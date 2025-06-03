<?php

namespace App\Traits;

use App\Models\menu;
use Illuminate\Support\Facades\Route;
use Filament\Notifications\Notification;
use App\Http\Controllers\PrivilegeController;

trait FilamentEditFunctions
{
    protected function beforeFill(): void
    {
        $routename = (isset($this->routename)) ? $this->routename : explode('/', str_replace(env('PANEL_PATH') . '/', '', Route::current()->uri))[0];
        if (!PrivilegeController::privilege_check(menu::where('url', $routename)->get()->pluck('id'), 2)) {
            Notification::make()->title('Sorry, you don`t have the privilege!')->warning()->send();
            redirect()->to(env('PANEL_PATH')."/{$routename}");
        }
    }
}
