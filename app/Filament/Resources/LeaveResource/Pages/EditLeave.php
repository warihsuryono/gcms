<?php

namespace App\Filament\Resources\LeaveResource\Pages;

use App\Filament\Resources\LeaveResource;
use App\Http\Controllers\PrivilegeController;
use App\Models\menu;
use App\Traits\FilamentEditFunctions;
use Exception;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditLeave extends EditRecord
{
    protected $routename = "leaves";
    protected static string $resource = LeaveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function beforeFill(): void{
        try{
            $allowed = false;
            $have_privilege = is_have_privilege($this->routename,2);
            if($this->getRecord()->created_by == Auth::user()->id && $have_privilege) $allowed = true; //creator and have privilege
            if(Auth::user()->id == 1) $allowed = true; // superuser

            if (!$allowed) {
                Notification::make()
                    ->danger()
                    ->title('Sorry, you don`t have the privilege!')
                    ->send();
                redirect(env('PANEL_PATH') . "/{$this->routename}");
            }
        }catch(Exception $e){
            Notification::make()->title('Edit Failed!')->body($e->getMessage())->danger()->send();
        }
    }
}
