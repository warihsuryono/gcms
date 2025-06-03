<?php

namespace App\Filament\Resources\ReimbursementResource\Pages;

use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Http\Controllers\PrivilegeController;
use App\Filament\Resources\ReimbursementResource;
use App\Models\FollowupOfficer;
use Livewire\Attributes\On;
use App\Models\menu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class EditReimbursement extends EditRecord
{
    protected static string $resource = ReimbursementResource::class;

    protected function beforeFill(): void
    {
        $allowed = false;
        $routename = "reimbursements";
        $have_privilege = PrivilegeController::privilege_check(menu::where('url', $routename)->get()->pluck('id'), 2);
        $paymentApprovers = FollowupOfficer::where('action', 'reimbursement-payment')->get()->pluck('user_id')->toArray();
        if ($this->getRecord()->user_id == Auth::user()->id && $have_privilege) $allowed = true; //creator and have privilege
        if (Auth::user()->id == 1) $allowed = true; // superuser
        if(in_array(Auth::user()->id, $paymentApprovers)) $allowed = true; // payment approver

        if (!$allowed) {
            Notification::make()->title('Sorry, you don`t have the privilege!')->warning()->send();
            redirect(env('PANEL_PATH') . '/' . request()->segment(2));
        } else if ($this->getRecord()->is_approved || $this->getRecord()->is_acknowledge) {
            Notification::make()->title('This document cannot be changed any further')->warning()->send();
            redirect(env('PANEL_PATH') . '/' . request()->segment(2));
        }
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    #[On('refreshReimbursement')]
    public function refresh(): void {}
}
