<?php

namespace App\Filament\Resources\ItemRequestResource\Pages;

use App\Models\ItemStock;
use App\Models\ItemMovement;
use App\Models\FollowupOfficer;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\ItemRequestResource;

class ViewItemRequest extends ViewRecord
{
    protected static string $resource = ItemRequestResource::class;
    protected static string $view = 'itemrequests.view';
    public $is_stock_visible = false;
    public $is_can_received = false;

    protected function beforeFill(): void
    {
        if (Auth::user()->privilege->id == 1) $this->is_stock_visible = true;
        if (@FollowupOfficer::where(['user_id' => Auth::user()->id, 'action' => 'item-request-issue'])->first()->id > 0) $this->is_stock_visible = true;
        if (@FollowupOfficer::where(['user_id' => Auth::user()->id, 'action' => 'stock-keeper'])->first()->id > 0) $this->is_stock_visible = true;

        if ($this->record->is_issued == 1) {
            if (Auth::user()->privilege->id == 1)  $this->is_can_received = true;
            if (Auth::user()->id == $this->record->created_by) $this->is_can_received = true;
        }
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

    public function item_request_issued()
    {
        $nowdatetime = now();
        foreach ($this->record->details as $item_request_detail) {
            $item_stock = ItemStock::where('item_id', $item_request_detail->item_id)->first();
            $item_stock->update(['qty' => $item_stock->qty - $item_request_detail->qty]);
            ItemMovement::create(['movement_at' => $nowdatetime, 'in_out' => 'out', 'item_movement_type_id' => $item_request_detail->item_movement_type_id, 'item_id' => $item_request_detail->item_id, 'qty' => $item_request_detail->qty, 'unit_id' => $item_request_detail->unit_id, 'notes' => $item_request_detail->notes]);
        }
        $this->record->update(['is_issued' => 1, 'issued_at' => $nowdatetime, 'issued_by' => Auth::user()->id]);
        Notification::make()->title('Item Request Issued!')->success()->send();
        $this->dispatch('refreshPage');
    }

    public function item_request_received()
    {
        $this->record->update(['is_received' => 1, 'received_at' => now(), 'received_by' => Auth::user()->id]);
        Notification::make()->title('Item Request Received!')->success()->send();
        $this->dispatch('refreshPage');
    }

    #[On('refreshPage')]
    public function refresh(): void {}
}
