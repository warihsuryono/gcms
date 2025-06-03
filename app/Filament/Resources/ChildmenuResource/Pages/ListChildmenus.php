<?php

namespace App\Filament\Resources\ChildmenuResource\Pages;

use App\Models\menu;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ChildmenuResource;
use App\Http\Controllers\PrivilegeController;

class ListChildmenus extends ListRecords
{
    protected static string $resource = ChildmenuResource::class;

    protected static function actions(): array
    {
        $routename = 'menus';
        $menu_ids = menu::where('url', $routename)->get()->pluck('id');
        $actions = [Action::make('menus')->label('Show Parent Menus')->url(App::make('url')->to(env('PANEL_PATH') . "/menus"))];
        if (PrivilegeController::privilege_check($menu_ids, 1))
            array_push($actions, CreateAction::make()->label('Add Child Menu')->icon('heroicon-o-plus'));
        return $actions;
    }

    protected function getHeaderActions(): array
    {
        $routename = 'menus';
        if (!PrivilegeController::privilege_check(menu::where('url', $routename)->get()->pluck('id')) && $routename != 'livewire') {
            Notification::make()
                ->title('Sorry, you don`t have the privilege!')
                ->warning()
                ->send();
            redirect(env('PANEL_PATH'));
        }
        return self::actions();
    }
}
