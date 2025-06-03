<?php

namespace App\Filament\Resources\PurchaseOrderResource\Pages;

use App\Models\menu;
use Livewire\Attributes\On;
use App\Models\PurchaseOrder;
use App\Models\FollowupOfficer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use App\Http\Controllers\PrivilegeController;
use App\Filament\Resources\PurchaseOrderResource;

class ViewPurchaseOrder extends ViewRecord
{
    protected static string $resource = PurchaseOrderResource::class;
    protected static string $view = 'purchaseorders.view';

    protected function getViewData(): array
    {
        $allowed = false;
        $routename = explode('/', str_replace(env('PANEL_PATH') . '/', '', Route::current()->uri))[0];
        $have_privilege = PrivilegeController::privilege_check(menu::where('url', $routename)->get()->pluck('id'), 4);
        if ($this->getRecord()->created_by == Auth::user()->id && $have_privilege) $allowed = true; //creator and have privilege
        if (Auth::user()->id == 1) $allowed = true; // superuser
        if (FollowupOfficer::whereLike('action', 'purchase-order-%')->where('user_id', Auth::user()->id)->first()) $allowed = true; //followup officer
        if (Auth::user()->employee && Auth::user()->employee->leader_user_id == 0) $allowed = true; // Director

        if (!$allowed) {
            Notification::make()->title('Sorry, you don`t have the privilege!')->warning()->send();
            redirect(env('PANEL_PATH') . '/' . request()->segment(2));
        }

        return [
            'im_approve_officer' => FollowupOfficer::where(['action' => 'purchase-request-approve', 'user_id' => Auth::user()->id])->first(),
            'im_authorize_officer' => FollowupOfficer::where(['action' => 'purchase-request-authorize', 'user_id' => Auth::user()->id])->first(),
        ];
    }

    public function approve($id)
    {
        PurchaseOrder::find($id)->update([
            'is_approved' => 1,
            'approved_at' => now(),
            'approved_by' => Auth::user()->id
        ]);
        Notification::make()->title('Approved')->success()->send();
        $this->dispatch('refreshPurchaseOrder');
    }

    public function authorizing($id)
    {
        PurchaseOrder::find($id)->update([
            'is_authorize' => 1,
            'authorize_at' => now(),
            'authorize_by' => Auth::user()->id
        ]);
        Notification::make()->title('Authorized')->success()->send();
        $this->dispatch('refreshPurchaseOrder');
    }

    #[On('refreshPurchaseOrder')]
    public function refresh(): void {}
}
