<?php

namespace App\Filament\Resources\ItemRequestResource\Pages;

use Filament\Actions;
use App\Models\FollowupOfficer;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\ItemRequestResource;

class ViewItemRequest extends ViewRecord
{
    protected static string $resource = ItemRequestResource::class;
    protected static string $view = 'itemrequests.view';
    public $is_stock_visible = false;

    protected function beforeFill(): void
    {
        if (Auth::user()->privilege->id == 1) $this->is_stock_visible = true;
        if (@FollowupOfficer::where(['user_id' => Auth::user()->id, 'action' => 'item-request-issue'])->first()->id > 0) $this->is_stock_visible = true;
        if (@FollowupOfficer::where(['user_id' => Auth::user()->id, 'action' => 'stock-keeper'])->first()->id > 0) $this->is_stock_visible = true;
    }

    public function work_order()
    {
        if (@$this->record->work_order_id > 0)
            redirect()->route('filament.' . env('PANEL_PATH') . '.resources.work-orders.view', $this->record->work_order_id);
    }

    public function create_purchase_order()
    {
        redirect()->route('filament.' . env('PANEL_PATH') . '.resources.purchase-orders.create', ['item_request_id' => $this->record->id]);
    }
}
