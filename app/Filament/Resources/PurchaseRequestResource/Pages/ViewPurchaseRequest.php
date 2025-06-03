<?php

namespace App\Filament\Resources\PurchaseRequestResource\Pages;

use App\Models\menu;
use Filament\Actions;
use App\Models\FollowupOfficer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use App\Http\Controllers\PrivilegeController;
use App\Filament\Resources\PurchaseRequestResource;
use App\Filament\Resources\PurchaseRequestResource\RelationManagers\DetailsRelationManager;
use App\Models\PurchaseRequest;
use Livewire\Attributes\On;

class ViewPurchaseRequest extends ViewRecord
{
    protected static string $resource = PurchaseRequestResource::class;
    protected static string $view = 'purchaserequests.view';


    protected function getViewData(): array
    {
        $allowed = false;
        $routename = explode('/', str_replace(env('PANEL_PATH') . '/', '', Route::current()->uri))[0];
        $have_privilege = PrivilegeController::privilege_check(menu::where('url', $routename)->get()->pluck('id'), 4);
        if ($this->getRecord()->created_by == Auth::user()->id && $have_privilege) $allowed = true; //creator and have privilege
        if (Auth::user()->id == 1) $allowed = true; // superuser
        if (FollowupOfficer::whereLike('action', 'purchase-request-%')->where('user_id', Auth::user()->id)->first()) $allowed = true; //followup officer
        if (Auth::user()->employee && Auth::user()->employee->leader_user_id == 0) $allowed = true; // Director

        if (!$allowed) {
            Notification::make()->title('Sorry, you don`t have the privilege!')->warning()->send();
            redirect(env('PANEL_PATH') . '/' . request()->segment(2));
        }

        return [
            'im_approve_officer' => FollowupOfficer::where(['action' => 'purchase-request-approve', 'user_id' => Auth::user()->id])->first(),
            'im_acknowledge_officer' => FollowupOfficer::where(['action' => 'purchase-request-acknowledge', 'user_id' => Auth::user()->id])->first(),
        ];
    }

    public function approve($id)
    {
        PurchaseRequest::find($id)->update([
            'is_approved' => 1,
            'approved_at' => now(),
            'approved_by' => Auth::user()->id
        ]);
        Notification::make()->title('Approved')->success()->send();
        $this->dispatch('refreshPurchaseRequest');
    }

    public function acknowledge($id)
    {
        PurchaseRequest::find($id)->update([
            'is_acknowledge' => 1,
            'acknowledge_at' => now(),
            'acknowledge_by' => Auth::user()->id
        ]);
        Notification::make()->title('Acknowledged')->success()->send();
        $this->dispatch('refreshPurchaseRequest');
    }

    public function createPurchaseOrder()
    {
        $this->dispatch('createPurchaseOrder');
    }

    #[On('refreshPurchaseRequest')]
    public function refresh(): void {}
}
