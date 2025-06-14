<?php

namespace App\Filament\Resources\ReimbursementResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ReimbursementResource;
use App\Models\FollowupOfficer;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Traits\FilamentCreateFunctions;
use Filament\Actions\Action;
use Filament\Notifications\Actions\Action as ActionsAction;
use Filament\Notifications\Notification;

class CreateReimbursement extends CreateRecord
{
    protected $routename = "reimbursements";
    use FilamentCreateFunctions;
    protected static string $resource = ReimbursementResource::class;
    protected static bool $canCreateAnother = false;
    protected function getRedirectUrl(): string
    {
        return route('filament.' . env('PANEL_PATH') . '.resources.reimbursements.edit', $this->record->id);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::user()->id;
        return $data;
    }

    protected function afterCreate(): void
    {
        // Select User Id from Followup Officer who have action = 'reimbursement-approve'
        $userIds = FollowupOfficer::where('action', 'reimbursement-approve')->get()->pluck('user_id')->toArray(); //returning Array
        // Select Users 
        $users = User::whereIn('id', $userIds)->get();
        $me = Auth::user();
        Notification::make()
            ->success()
            ->title('Reimbursement has been created!')
            ->body("{$me->name} has created a new reimbursement. Please check it out.")
            ->actions([
                ActionsAction::make('View Details')->url(route('filament.' . env('PANEL_PATH') . '.resources.reimbursements.view', $this->record->id)),
            ])
            ->sendToDatabase($users, true);
    }
}
