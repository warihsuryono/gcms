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
        $nowdatetime = now();
        foreach ($this->record->details as $stock_opname_detail) {
            $item_stock = ItemStock::where('item_id', $stock_opname_detail->item_id)->first();
            if ($item_stock->qty > $stock_opname_detail->actual_qty) {
                $in_out = 'out';
                $qty = $item_stock->qty - $stock_opname_detail->actual_qty;
            } else {
                $in_out = 'in';
                $qty = $stock_opname_detail->actual_qty - $item_stock->qty;
            }
            $item_stock->update(['qty' => $stock_opname_detail->actual_qty]);
            ItemMovement::create([
                'movement_at' => $nowdatetime,
                'in_out' => $in_out,
                'item_movement_type_id' => 4,
                'doc_no' => 'Stock Opname #' . $this->record->id,
                'item_receipt_detail_id' => '',
                'item_id' => $stock_opname_detail->item_id,
                'qty' => $qty,
                'unit_id' => $stock_opname_detail->unit_id,
                'notes' => $stock_opname_detail->notes
            ]);
        }
        $this->record->update(['is_approved' => 1, 'approved_at' => $nowdatetime, 'approved_by' => Auth::user()->id]);

        Notification::make()->title('Stock Opname Approved!')->success()->send();
        $this->dispatch('refreshStockOpname');
    }

    #[On('refreshStockOpname')]
    public function refresh(): void {}
}
