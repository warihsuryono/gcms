<?php

namespace App\Filament\Resources\PurchaseOrderResource\Pages;

use App\Models\menu;
use Livewire\Attributes\On;
use App\Models\PurchaseOrder;
use App\Models\FollowupOfficer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use App\Http\Controllers\PrivilegeController;
use App\Filament\Resources\PurchaseOrderResource;

class ViewPurchaseOrder extends ViewRecord
{
    protected static string $resource = PurchaseOrderResource::class;
    protected static string $view = 'purchaseorders.view';
    public $is_approve_officer = false;
    public $is_send_officer = false;
    public $is_close_officer = false;
    public $is_stock_keeper = false;

    protected static ?string $title = 'Purchase Requests';
    public function getBreadcrumbs(): array
    {
        return [
            route('filament.' . env('PANEL_PATH') . '.resources.purchase-orders.index') => 'Purchase Requests',
            'View'
        ];
    }

    public function getHeaderActions(): array
    {
        $actions = [];
        $allowed = false;
        $routename = explode('/', str_replace(env('PANEL_PATH') . '/', '', Route::current()->uri))[0];
        $have_privilege = PrivilegeController::privilege_check(menu::where('url', $routename)->get()->pluck('id'), 4);
        if ($this->record->created_by == Auth::user()->id && $have_privilege) $allowed = true; //creator and have privilege
        if (Auth::user()->id == 1) $allowed = true; // superuser
        if (FollowupOfficer::whereLike('action', 'purchase-order-%')->where('user_id', Auth::user()->id)->first()) $allowed = true; //followup officer

        if (!$allowed) {
            Notification::make()->title('Sorry, you don`t have the privilege!')->warning()->send();
            redirect(env('PANEL_PATH') . '/' . request()->segment(2));
        }

        if (Auth::user()->privilege->id == 1) $this->is_approve_officer = true;
        if (@FollowupOfficer::where(['user_id' => Auth::user()->id, 'action' => 'purchase-order-approve'])->first()->id > 0) $this->is_approve_officer = true;

        if ($this->record->is_approved == 1) {
            if (Auth::user()->privilege->id == 1) {
                $this->is_send_officer = true;
                $this->is_close_officer = true;
                $this->is_stock_keeper = true;
            }
            if (@FollowupOfficer::where(['user_id' => Auth::user()->id, 'action' => 'purchase-order-send'])->first()->id > 0) $this->is_send_officer = true;
            if (@FollowupOfficer::where(['user_id' => Auth::user()->id, 'action' => 'purchase-order-close'])->first()->id > 0) $this->is_close_officer = true;
            if (@FollowupOfficer::where(['user_id' => Auth::user()->id, 'action' => 'stock-keeper'])->first()->id > 0) $this->is_stock_keeper = true;
        }

        if ($this->record->is_approved == 0 && $this->is_approve_officer)
            $actions = array_merge($actions, [
                Action::make('approve')
                    ->label('Approve')
                    ->requiresConfirmation()
                    ->modalHeading('Confirm Approval')
                    ->modalDescription('Are you sure you want to approve this purchase order?')
                    ->action(fn() => $this->approve($this->record->id))
                    ->icon('heroicon-o-check')
                    ->color('success')
            ]);

        if ($this->record->is_sent == 0 && $this->is_send_officer)
            $actions = array_merge($actions, [
                Action::make('send')
                    ->label('Send')
                    ->requiresConfirmation()
                    ->modalHeading('Confirm Sending')
                    ->modalDescription('Are you sure you want to send this purchase order?')
                    ->action(fn() => $this->send($this->record->id))
                    ->icon('heroicon-o-paper-airplane')
                    ->color('primary')
            ]);

        if ($this->record->is_closed == 0 && $this->is_close_officer)
            $actions = array_merge($actions, [
                Action::make('close')
                    ->label('Close')
                    ->requiresConfirmation()
                    ->modalHeading('Confirm Closing')
                    ->modalDescription('Are you sure you want to close this purchase order?')
                    ->action(fn() => $this->close($this->record->id))
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
            ]);

        if ($this->record->is_closed == 0 && $this->is_stock_keeper)
            $actions = array_merge($actions, [
                Action::make('create_item_receipt')
                    ->label('Create Item Receipt')
                    ->requiresConfirmation()
                    ->modalHeading('Confirm Item Receipt Creation')
                    ->modalDescription('Are you sure you want to create an item receipt for this purchase order?')
                    ->action(fn() => redirect()->route('filament.' . env('PANEL_PATH') . '.resources.item-receipts.create', ['purchase_order_id' => $this->record->id]))
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
            ]);


        return $actions;
    }

    public function approve($id)
    {
        PurchaseOrder::find($id)->update([
            'is_approved' => 1,
            'approved_at' => now(),
            'approved_by' => Auth::user()->id
        ]);
        Notification::make()->title('Purchase Order Approved')->success()->send();
        $this->dispatch('refreshPurchaseOrder');
    }

    public function send($id)
    {
        PurchaseOrder::find($id)->update([
            'is_sent' => 1,
            'sent_at' => now(),
            'sent_by' => Auth::user()->id
        ]);
        Notification::make()->title('Purchase Order Sent')->success()->send();
        $this->dispatch('refreshPurchaseOrder');
    }

    public function close($id)
    {
        PurchaseOrder::find($id)->update([
            'is_closed' => 1,
            'closed_at' => now(),
            'closed_by' => Auth::user()->id
        ]);
        Notification::make()->title('Purchase Order Closed')->success()->send();
        $this->dispatch('refreshPurchaseOrder');
    }

    #[On('refreshPurchaseOrder')]
    public function refresh(): void {}
}
