<?php

namespace App\Filament\Resources\LeaveResource\Pages;

use App\Filament\Resources\LeaveResource;
use App\Http\Controllers\PrivilegeController;
use App\Models\FollowupOfficer;
use App\Models\Leave;
use App\Models\menu;
use Exception;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Actions\Action as ActionsAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;

class ViewLeave extends ViewRecord
{
    protected static string $resource = LeaveResource::class;

    protected static string $view = 'leaves.view';

    public function getActions(): array{
        $actions = [];
        if($this->record->document){
            $actions[] = Action::make('Download Document')
            ->color('gray')
            ->icon('heroicon-o-arrow-down-tray')
            ->action(function(){
                try{
                    return response()->download("storage/".$this->record->document);
                }catch(\Exception $e){
                    Notification::make()->title('Download Failed!')->danger()->send();
                }
            });
        }
        if(is_officer(Auth::user()->id, 'leave-approve') && $this->record->is_approved == 0){
            $actions[] = Action::make('Approve')->color('success')->icon('heroicon-o-check-circle')->action(function(){
                $this->approve();
            });
        }
        if(is_officer(Auth::user()->id, 'leave-acknowledge') && $this->record->is_approved == 1 && $this->record->is_acknowledge == 0){
            $actions[] = Action::make('Acknowledge')->color('info')->icon('heroicon-o-document-check')->action(function(){
                $this->acknowledge();
            });
        }
        return $actions;
    }

    public function getViewData():array{
        $allowed = false;
        $routename = "leaves";
        $have_privilege = PrivilegeController::privilege_check(menu::where('url', $routename)->get()->pluck('id'), 4);
        if ($this->getRecord()->user_id == Auth::user()->id && $have_privilege) $allowed = true; //creator and have privilege
        if (Auth::user()->id == 1) $allowed = true; // superuser
        if (is_officer(Auth::user()->id, 'leave-%')) $allowed = true; //followup officer
        if (Auth::user()->employee && Auth::user()->employee->leader_user_id == 0) $allowed = true; // Director

        if (!$allowed) {
            Notification::make()
                ->title('Sorry, you don`t have the privilege!')
                ->warning()
                ->send();
            
            redirect(env('PANEL_PATH') . "/{$routename}");
        }

        return [
            'im_approve_officer' => FollowupOfficer::where(['action' => 'leave-approve', 'user_id' => Auth::user()->id])->first(),
            'im_acknowledge_officer' => FollowupOfficer::where(['action' => 'leave-acknowledge', 'user_id' => Auth::user()->id])->first(),
        ];
    }


    public function approve():void{
        try{
            $this->record->update([
                'is_approved' => 1,
                'approved_at' => now(),
                'approved_by' => Auth::user()->id
            ]);
            Notification::make()->title('Document Approved!')->success()->send();
            Notification::make()
                ->title('Leave has been approved!')
                ->body(Auth::user()->name." has approved your leave request. Please check it out.")
                ->success()
                ->actions([
                    ActionsAction::make('View Details')->url(route('filament.room.resources.leaves.view', $this->record->id)),
                ])->toDatabase([$this->record->user], true);

            Notification::make()
                ->title('Leave has been approved!')
                ->body(Auth::user()->name." has approved ".$this->record->user->name." leave request. Please check it out.")
                ->success()
                ->actions([
                    ActionsAction::make('View Detail')->url(route('filament.room.resources.leaves.view', $this->record->id)),
                ])->toDatabase(get_users_officer_by_action("leave-acknowledge"), true);
            
            $this->refresh();

        }catch(Exception $e){
            Notification::make()->title('Approve Failed!')->body($e->getMessage())->danger()->send();
        }
    }

    public function acknowledge():void{
        try{
            $this->record->update([
                'is_acknowledge' => 1,
                'acknowledge_at' => now(),
                'acknowledge_by' => Auth::user()->id
            ]);
            Notification::make()->title('Document Acknowledged!')->success()->send();
            Notification::make()
                ->title('Leave has been acknowledged!')
                ->body(Auth::user()->name." has acknowledged your leave request. Please check it out.")
                ->success()
                ->actions([
                    ActionsAction::make('View Details')->url(route('filament.room.resources.leaves.view', $this->record->id)),
                ])->toDatabase([$this->record->user], true);
            $this->refresh();

        }catch(Exception $e){
            Notification::make()->title('Acknowledge Failed!')->danger()->send();
        }
    }

    public function refresh():void{
        redirect(route("filament.room.resources.leaves.view", $this->record->id));
    }
}
