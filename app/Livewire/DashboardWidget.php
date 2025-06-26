<?php

namespace App\Livewire;

use App\Models\FollowupOfficer;
use App\Models\ItemRequest;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class DashboardWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '60s';
    protected function getStats(): array
    {
        $widgets = [];
        $is_allowed_open_item_request = false;

        if (Auth::user()->privilege->id == 1) $is_allowed_open_item_request = true;
        if (@FollowupOfficer::where(['user_id' => Auth::user()->id, 'action' => 'item-request-issue'])->first()->id > 0) $is_allowed_open_item_request = true;
        if (@FollowupOfficer::where(['user_id' => Auth::user()->id, 'action' => 'stock-keeper'])->first()->id > 0) $is_allowed_open_item_request = true;

        if ($is_allowed_open_item_request) {
            $open_item_requests = count(ItemRequest::where('is_issued', 0)->get());
            $item_requests_this_month = count(ItemRequest::where('item_request_at', 'like', date('Y-m-') . '%')->get());
            $widgets = [
                Stat::make('Open Item Requests', $open_item_requests)
                    ->icon('heroicon-o-cube')
                    ->description("Total Item Request This Month : " . $item_requests_this_month)
                    ->extraAttributes([
                        'class' => 'cursor-pointer',
                        'wire:click' => 'goToItemRequests',
                    ]),
            ];
        }

        return $widgets;
    }

    public function goToItemRequests()
    {
        return redirect()->route('filament.' . env('PANEL_PATH') . '.resources.item-requests.index', ['is_open' => 1]);
    }
}
