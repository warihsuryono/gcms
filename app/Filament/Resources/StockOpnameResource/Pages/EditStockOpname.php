<?php

namespace App\Filament\Resources\StockOpnameResource\Pages;

use App\Filament\Resources\StockOpnameResource;
use Filament\Resources\Pages\EditRecord;
use Livewire\Attributes\On;
use Filament\Notifications\Notification;
use App\Http\Controllers\PrivilegeController;
use App\Models\menu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class EditStockOpname extends EditRecord
{
    protected static string $resource = StockOpnameResource::class;

    protected function beforeFill(): void
    {
        $allowed = false;
        $routename = explode('/', str_replace(env('PANEL_PATH') . '/', '', Route::current()->uri))[0];
        $have_privilege = PrivilegeController::privilege_check(menu::where('url', $routename)->get()->pluck('id'), 2);
        if ($this->getRecord()->user_id == Auth::user()->id && $have_privilege) $allowed = true; //creator and have privilege
        if (Auth::user()->id == 1) $allowed = true; // superuser

        if (!$allowed) {
            Notification::make()->title('Sorry, you don`t have the privilege!')->warning()->send();
            redirect(env('PANEL_PATH') . '/' . request()->segment(2));
        } else if ($this->getRecord()->is_approved) {
            Notification::make()->title('This document cannot be changed any further')->warning()->send();
            redirect(env('PANEL_PATH') . '/' . request()->segment(2));
        }
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    #[On('refreshStockOpname')]
    public function refresh(): void {}
}
