<?php

namespace App\Filament\Resources\PurchaseOrderResource\Pages;

use App\Models\menu;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Http\Controllers\PrivilegeController;
use App\Filament\Resources\PurchaseOrderResource;

class EditPurchaseOrder extends EditRecord
{
    protected static string $resource = PurchaseOrderResource::class;


    protected static ?string $title = 'Edit Purchase Requests';
    public function getBreadcrumbs(): array
    {
        return [
            route('filament.' . env('PANEL_PATH') . '.resources.purchase-orders.index') => 'Purchase Requests',
            'Edit'
        ];
    }

    protected function beforeFill(): void
    {
        $allowed = false;
        $routename = explode('/', str_replace(env('PANEL_PATH') . '/', '', Route::current()->uri))[0];
        $have_privilege = PrivilegeController::privilege_check(menu::where('url', $routename)->get()->pluck('id'), 2);
        if ($this->getRecord()->created_by == Auth::user()->id && $have_privilege) $allowed = true; //creator and have privilege
        if (Auth::user()->id == 1) $allowed = true; // superuser

        if (!$allowed) {
            Notification::make()->title('Sorry, you don`t have the privilege!')->warning()->send();
            redirect(env('PANEL_PATH') . '/' . request()->segment(2));
        } else if ($this->getRecord()->is_approved || $this->getRecord()->is_acknowledge) {
            Notification::make()->title('This document cannot be changed any further')->warning()->send();
            redirect(env('PANEL_PATH') . '/' . request()->segment(2));
        }
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    #[On('refreshPurchaseOrder')]
    public function refresh(): void {}
}
