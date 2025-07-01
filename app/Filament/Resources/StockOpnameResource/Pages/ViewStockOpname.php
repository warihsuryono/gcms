<?php

namespace App\Filament\Resources\StockOpnameResource\Pages;

use App\Filament\Resources\StockOpnameResource;
use Filament\Resources\Pages\ViewRecord;
use App\Models\menu;
use App\Models\ItemStock;
use Livewire\Attributes\On;
use App\Models\ItemMovement;
use Filament\Actions\Action;
use App\Models\FollowupOfficer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Filament\Notifications\Notification;
use App\Http\Controllers\PrivilegeController;
use App\Models\ItemReceipt;
use App\Models\PurchaseOrder;

class ViewStockOpname extends ViewRecord
{
    protected static string $resource = StockOpnameResource::class;
    protected static string $view = 'stockopnames.view';
    protected static ?string $title = 'Stock Opname';
    public $is_approve_officer = false;
    public $is_stock_keeper = false;


    public function getHeaderActions(): array
    {
        $actions = [];
        $allowed = false;
        $routename = explode('/', str_replace(env('PANEL_PATH') . '/', '', Route::current()->uri))[0];
        $have_privilege = PrivilegeController::privilege_check(menu::where('url', $routename)->get()->pluck('id'), 4);
        if ($this->record->created_by == Auth::user()->id && $have_privilege) $allowed = true; //creator and have privilege
        if (Auth::user()->id == 1) $allowed = true; // superuser
        if (FollowupOfficer::whereLike('action', 'stock-opname-%')->where('user_id', Auth::user()->id)->first()) $allowed = true; //followup officer
        if (FollowupOfficer::whereLike('action', 'stock-keeper%')->where('user_id', Auth::user()->id)->first()) $allowed = true; //followup officer

        if (!$allowed) {
            Notification::make()->title('Sorry, you don`t have the privilege!')->warning()->send();
            redirect(env('PANEL_PATH') . '/' . request()->segment(2));
        }

        if (Auth::user()->privilege->id == 1) $this->is_approve_officer = true;
        if (@FollowupOfficer::where(['user_id' => Auth::user()->id, 'action' => 'stock-opname-approve'])->first()->id > 0) $this->is_approve_officer = true;

        if ($this->record->is_approved == 1) {
            if (Auth::user()->privilege->id == 1) $this->is_stock_keeper = true;
            if (@FollowupOfficer::where(['user_id' => Auth::user()->id, 'action' => 'stock-keeper'])->first()->id > 0) $this->is_stock_keeper = true;
        }

        if ($this->record->is_approved == 0 && $this->is_approve_officer)
            $actions = array_merge($actions, [
                Action::make('approve')
                    ->label('Approve')
                    ->requiresConfirmation()
                    ->modalHeading('Confirm Approval')
                    ->modalDescription('Are you sure you want to approve this stock opname? This will update the stock quantity and cannot be undone.')
                    ->action(fn() => $this->approve())
                    ->icon('heroicon-o-check')
                    ->color('success')
            ]);


        return $actions;
    }

    public function approve()
    {
        ////////////////iniiiiiiihhhhh
        $nowdatetime = now();
        foreach ($this->record->details as $item_receipt_detail) {
            $item_stock = ItemStock::where('item_id', $item_receipt_detail->item_id)->first();
            $item_stock->update(['qty' => $item_stock->qty + $item_receipt_detail->qty]);
            ItemMovement::create([
                'movement_at' => $nowdatetime,
                'in_out' => 'in',
                'item_movement_type_id' => 3,
                'doc_no' => $this->record->item_receipt_no,
                'item_receipt_detail_id' => $item_receipt_detail->id,
                'item_id' => $item_receipt_detail->item_id,
                'qty' => $item_receipt_detail->qty,
                'unit_id' => $item_receipt_detail->unit_id,
                'notes' => $item_receipt_detail->notes
            ]);
        }
        $this->record->update(['is_approved' => 1, 'approved_at' => $nowdatetime, 'approved_by' => Auth::user()->id]);

        $is_po_fulfilled = true;
        $qty_received = [];
        $item_receipts = ItemReceipt::where('purchase_order_id', $this->record->purchase_order_id)->where('is_approved', 1)->get();
        if ($item_receipts->count() > 0) {
            foreach ($item_receipts as $receipt) {
                foreach ($receipt->details as $detail) {
                    if (!isset($qty_received[$detail->item_id])) $qty_received[$detail->item_id] = 0;
                    $qty_received[$detail->item_id] += $detail->qty;
                }
            }
        }

        foreach ($this->record->purchase_order->details as $purchase_order_detail) {
            if (@$qty_received[$purchase_order_detail->item_id] < $purchase_order_detail->qty) {
                $is_po_fulfilled = false;
                break;
            }
        }

        if ($is_po_fulfilled) {
            $this->record->purchase_order->update(['is_closed' => 1, 'closed_at' => $nowdatetime, 'closed_by' => Auth::user()->id]);
            Notification::make()->title('Purchase Order Fulfilled!')->success()->send();
        }

        Notification::make()->title('Item Receipt Approved!')->success()->send();
        $this->dispatch('refreshItemReceipt');
    }

    #[On('refreshItemReceipt')]
    public function refresh(): void {}
}
