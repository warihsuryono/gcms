<?php

namespace App\Filament\Resources\PurchaseOrderResource\Pages;

use App\Filament\Resources\PurchaseOrderResource;
use App\Traits\FilamentListFunctions;
use Filament\Resources\Pages\ListRecords;
use App\Models\menu;
use Filament\Actions\CreateAction;
use App\Http\Controllers\PrivilegeController;
use Filament\Notifications\Notification;

class ListPurchaseOrders extends ListRecords
{
    // use FilamentListFunctions;
    protected $routename = 'purchase-orders';
    protected static string $resource = PurchaseOrderResource::class;
    protected static ?string $title = 'Purchase Requests';
    public function getBreadcrumbs(): array
    {
        return [
            route('filament.' . env('PANEL_PATH') . '.resources.purchase-orders.index') => 'Purchase Requests',
            'List'
        ];
    }

    protected function actions(): array
    {
        $routename = $this->routename;
        $menu_ids = menu::where('url', $routename)->get()->pluck('id');
        $actions = [];
        if (PrivilegeController::privilege_check($menu_ids, 1))
            array_push($actions, CreateAction::make()->icon('heroicon-o-plus')->label('Create Purchase Request'));
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
