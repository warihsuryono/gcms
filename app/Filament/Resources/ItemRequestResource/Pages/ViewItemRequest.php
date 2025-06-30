<?php

namespace App\Filament\Resources\ItemRequestResource\Pages;

use App\Models\ItemStock;
use Livewire\Attributes\On;
use App\Models\ItemMovement;
use Filament\Actions\Action;
use App\Models\FollowupOfficer;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\ItemRequestResource;

class ViewItemRequest extends ViewRecord
{
    protected static string $resource = ItemRequestResource::class;
    protected static string $view = 'itemrequests.view';
    protected static ?string $title = 'Item Request';
    public $is_stock_visible = false;
    public $is_can_received = false;

    public function getHeaderActions(): array
    {
        $actions = [];

        if ($this->is_stock_visible)
            $actions = array_merge($actions, [
                Action::make('create_purchase_order')
                    ->label('Create Purchase Order')
                    ->requiresConfirmation()
                    ->modalHeading('Confirm Purchase Order Creation')
                    ->modalDescription('Are you sure you want to create a purchase order for all understock items?')
                    ->action(fn() => $this->create_purchase_order())
                    ->icon('heroicon-o-plus')
                    ->color('success')
            ]);

        if ($this->is_stock_visible && $this->record->is_issued == 0)
            $actions = array_merge($actions, [
                Action::make('item_request_issued')
                    ->label('Item Request Issued')
                    ->requiresConfirmation()
                    ->modalHeading('Confirm Item Request Issued')
                    ->modalDescription('Are you sure you want to mark this item request as issued?, this will reduce the stock of all items in this request.')
                    ->action(fn() => $this->item_request_issued())
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('warning')
            ]);

        if ($this->is_can_received && $this->record->is_received == 0)
            $actions = array_merge($actions, [
                Action::make('item_request_received')
                    ->label('item_request_received')
                    ->requiresConfirmation()
                    ->modalHeading('Confirm Item Request Received')
                    ->modalDescription('Are you sure you want to mark this item request as received?')
                    ->action(fn() => $this->item_request_received())
                    ->icon('heroicon-o-plus')
                    ->color('success')
            ]);
        return $actions;
    }

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
            ItemMovement::create([
                'movement_at' => $nowdatetime,
                'in_out' => 'out',
                'item_movement_type_id' => $item_request_detail->item_movement_type_id,
                'doc_no' => $this->record->item_request_no,
                'item_request_detail_id' => $item_request_detail->id,
                'item_id' => $item_request_detail->item_id,
                'qty' => $item_request_detail->qty,
                'unit_id' => $item_request_detail->unit_id,
                'notes' => $item_request_detail->notes
            ]);
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
