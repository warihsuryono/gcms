<?php

namespace App\Filament\Resources\ReimbursementResource\Pages;

use App\Models\menu;
use Filament\Actions;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Reimbursement;
use App\Models\FollowupOfficer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use App\Http\Controllers\PrivilegeController;
use App\Filament\Resources\ReimbursementResource;
use App\Models\User;
use Filament\Notifications\Actions\Action;

class ViewReimbursement extends ViewRecord
{
    protected static string $resource = ReimbursementResource::class;
    protected static string $view = 'reimbursements.view';

    protected function getViewData(): array
    {
        $allowed = false;
        $routename = "reimbursements";
        $have_privilege = PrivilegeController::privilege_check(menu::where('url', $routename)->get()->pluck('id'), 4);
        if ($this->getRecord()->user_id == Auth::user()->id && $have_privilege) $allowed = true; //creator and have privilege
        if (Auth::user()->id == 1) $allowed = true; // superuser
        if (FollowupOfficer::whereLike('action', 'reimbursement-%')->where('user_id', Auth::user()->id)->first()) $allowed = true; //followup officer
        if (Auth::user()->employee && Auth::user()->employee->leader_user_id == 0) $allowed = true; // Director

        if (!$allowed) {
            Notification::make()
                ->title('Sorry, you don`t have the privilege!')
                ->warning()
                ->send();
            redirect(env('PANEL_PATH') . '/' . request()->segment(2));
        }

        return [
            'im_approve_officer' => FollowupOfficer::where(['action' => 'reimbursement-approve', 'user_id' => Auth::user()->id])->first(),
            'im_acknowledge_officer' => FollowupOfficer::where(['action' => 'reimbursement-acknowledge', 'user_id' => Auth::user()->id])->first(),
        ];
    }

    public function approve()
    {
        $this->record->update([
            'is_approved' => 1,
            'approved_at' => now(),
            'approved_by' => Auth::user()->id
        ]);
        // Create Notification to user
        $actionNotifications = [
            Action::make('View Details')->url(route('filament.' . env('PANEL_PATH') . '.resources.reimbursements.view', $this->record->id)),
            Actions\DeleteAction::make(),
        ];
        $acknowlegedUsers = FollowupOfficer::where('action', 'reimbursement-acknowledge')->pluck('user_id')->toArray();
        $acknowlegedUsers = User::whereIn('id', $acknowlegedUsers)->get()->toArray();

        // Sent Popup Notification to user approved
        Notification::make()->title('Approved Reimbursement Success!')->success()->send();

        // Sent Notification to user acknowledged
        Notification::make()
            ->title('Reimbursement has been approved!')
            ->body(Auth::user()->name . " has approved " . $this->record->user->name . " reimbursement request. Please check it out.")
            ->success()
            ->actions($actionNotifications)
            ->sendToDatabase($acknowlegedUsers, true);

        // Sent Notification to user who create reimbursement
        Notification::make()
            ->title('Reimbursement has been approved!')
            ->body(Auth::user()->name . " has approved your reimbursement request. Please check it out.")
            ->success()
            ->actions($actionNotifications)
            ->sendToDatabase([$this->record->user], true);

        $this->dispatch('refreshReimbursement');
    }

    public function acknowledge()
    {
        $this->record->update([
            'is_acknowledge' => 1,
            'acknowledge_at' => now(),
            'acknowledge_by' => Auth::user()->id
        ]);
        // Sent Popup Notification to user acknowledged
        Notification::make()->title('Acknowledged Reimbursement Success!')->success()->send();

        // Sent Popup Notification to user who create reimbursement
        Notification::make()
            ->title('Acknowledged')
            ->body(Auth::user()->name . " has acknowledged your reimbursement request. Please check it out.")
            ->success()
            ->actions([
                Action::make('View Details')->url(route('filament.' . env('PANEL_PATH') . '.resources.reimbursements.view', $this->record->id)),
                Actions\DeleteAction::make(),
            ])
            ->sendToDatabase([$this->record->user], true);
        $this->dispatch('refreshReimbursement');
    }

    #[On('refreshReimbursement')]
    public function refresh(): void {}
}
