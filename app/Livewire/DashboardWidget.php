<?php

namespace App\Livewire;

use App\Models\Item;
use App\Models\ItemStock;
use App\Models\ItemRequest;
use App\Models\FollowupOfficer;
use App\Models\PurchaseOrder;
use App\Models\UrgentWorkOrder;
use Illuminate\Support\Facades\Auth;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class DashboardWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '60s';

    protected function getColumns(): int
    {
        return 2;
    }

    protected function getStats(): array
    {
        $widgets = [];
        $is_allowed_open_item_request = false;
        $is_allowed_open_purchase_order = false;
        $is_allowed_understock = false;

        if (@FollowupOfficer::where(['user_id' => Auth::user()->id, 'action' => 'item-request-issue'])->first()->id > 0) $is_allowed_open_item_request = true;
        if (@FollowupOfficer::where(['user_id' => Auth::user()->id, 'action' => 'stock-keeper'])->first()->id > 0) {
            $is_allowed_open_item_request = true;
            $is_allowed_open_purchase_order = true;
            $is_allowed_understock = true;
        }
        if (@FollowupOfficer::where(['user_id' => Auth::user()->id, 'action' => 'stock-keeper-leader'])->first()->id > 0) {
            $is_allowed_open_item_request = true;
            $is_allowed_open_purchase_order = true;
            $$is_allowed_understock = true;
        }
        if (@FollowupOfficer::where(['user_id' => Auth::user()->id, 'action' => 'purchase-order-approve'])->first()->id > 0) {
            $is_allowed_open_item_request = true;
            $is_allowed_open_purchase_order = true;
            $is_allowed_understock = true;
        }

        $urgent_work_orders = count(UrgentWorkOrder::where('work_order_status_id', '<', 3)->get());
        $widgets = array_merge($widgets, [
            Stat::make('Number of Urgent Work Orders', $urgent_work_orders . ' items')
                ->icon('heroicon-o-cog')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                    'wire:click' => 'goToUrgentWorkOrders',
                ])
        ]);

        if ($is_allowed_open_item_request || Auth::user()->privilege->id == 1) {
            $open_item_requests = count(ItemRequest::where('is_issued', 0)->get());
            $item_requests_this_month = count(ItemRequest::where('item_request_at', 'like', date('Y-m-') . '%')->get());
            $widgets = array_merge($widgets, [
                Stat::make('Outstanding Item Requests', $open_item_requests)
                    ->icon('heroicon-o-cube')
                    ->description("Total Item Request This Month : " . $item_requests_this_month)
                    ->extraAttributes([
                        'class' => 'cursor-pointer',
                        'wire:click' => 'goToItemRequests',
                    ])
            ]);
        }

        if ($is_allowed_open_purchase_order || Auth::user()->privilege->id == 1) {
            $open_purchase_order = count(PurchaseOrder::where('is_closed', 0)->get());
            $purchase_orders_this_month = count(PurchaseOrder::where('doc_at', 'like', date('Y-m-') . '%')->get());
            $widgets = array_merge($widgets, [
                Stat::make('Outstanding Purchase Request', $open_purchase_order)
                    ->icon('heroicon-o-shopping-cart')
                    ->description("Total Purchase Requests This Month : " . $purchase_orders_this_month)
                    ->extraAttributes([
                        'class' => 'cursor-pointer',
                        'wire:click' => 'goToPurchaseOrders',
                    ])
            ]);
        }

        if ($is_allowed_understock || Auth::user()->privilege->id == 1) {
            $understock = count(Item::understock_items()->get());
            $widgets = array_merge($widgets, [
                Stat::make('Number of Understocked Items', $understock . ' items')
                    ->icon('heroicon-o-exclamation-circle')
                    ->extraAttributes([
                        'class' => 'cursor-pointer',
                        'wire:click' => 'goToUnderStock',
                    ])
            ]);
        }

        return $widgets;
    }

    public function goToItemRequests()
    {
        return redirect()->route('filament.' . env('PANEL_PATH') . '.resources.item-requests.index', ['is_open' => 1]);
    }

    public function goToPurchaseOrders()
    {
        return redirect()->route('filament.' . env('PANEL_PATH') . '.resources.purchase-orders.index', ['is_open' => 1]);
    }

    public function goToUnderStock()
    {
        return redirect()->route('filament.' . env('PANEL_PATH') . '.resources.items.understock');
    }

    public function goToUrgentWorkOrders()
    {
        return redirect()->route('filament.' . env('PANEL_PATH') . '.resources.urgent-work-orders.index');
    }
}
