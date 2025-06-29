<?php

namespace App\Livewire;

use App\Models\Item;
use App\Models\ItemStock;
use App\Models\ItemRequest;
use App\Models\FollowupOfficer;
use Illuminate\Support\Facades\Auth;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class DashboardWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '60s';
    protected function getStats(): array
    {
        $widgets = [];
        $is_allowed_open_item_request = false;
        $is_allowed_understock = false;

        if (@FollowupOfficer::where(['user_id' => Auth::user()->id, 'action' => 'item-request-issue'])->first()->id > 0) $is_allowed_open_item_request = true;
        if (@FollowupOfficer::where(['user_id' => Auth::user()->id, 'action' => 'stock-keeper'])->first()->id > 0) {
            $is_allowed_open_item_request = true;
            $is_allowed_understock = true;
        }
        if (@FollowupOfficer::where(['user_id' => Auth::user()->id, 'action' => 'stock-keeper-leader'])->first()->id > 0) {
            $is_allowed_open_item_request = true;
            $$is_allowed_understock = true;
        }
        if (@FollowupOfficer::where(['user_id' => Auth::user()->id, 'action' => 'purchase-order-authorize'])->first()->id > 0) {
            $is_allowed_open_item_request = true;
            $is_allowed_understock = true;
        }

        if ($is_allowed_open_item_request || Auth::user()->privilege->id == 1) {
            $open_item_requests = count(ItemRequest::where('is_issued', 0)->get());
            $item_requests_this_month = count(ItemRequest::where('item_request_at', 'like', date('Y-m-') . '%')->get());
            $widgets = array_merge($widgets, [
                Stat::make('Open Item Requests', $open_item_requests)
                    ->icon('heroicon-o-cube')
                    ->description("Total Item Request This Month : " . $item_requests_this_month)
                    ->extraAttributes([
                        'class' => 'cursor-pointer',
                        'wire:click' => 'goToItemRequests',
                    ])
            ]);
        }

        if ($is_allowed_understock || Auth::user()->privilege->id == 1) {
            // $understock = 0;
            // foreach (ItemStock::all() as $item_stock) if ($item_stock->qty < $item_stock->item->minimum_stock) $understock++;
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

    public function goToUnderStock()
    {
        return redirect()->route('filament.' . env('PANEL_PATH') . '.resources.items.understock', 1);
    }
}
