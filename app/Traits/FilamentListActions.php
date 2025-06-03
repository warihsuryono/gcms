<?php

namespace App\Traits;

use App\Models\menu;
use App\Http\Controllers\PrivilegeController;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\DeleteAction;

trait FilamentListActions
{
    protected static function actions($routename): array
    {
        $menu_ids = menu::where('url', $routename)->get()->pluck('id');
        $actions = [];
        if (PrivilegeController::privilege_check($menu_ids, 4))
            array_push($actions, ViewAction::make()->iconButton());
        if (PrivilegeController::privilege_check($menu_ids, 2))
            array_push($actions, EditAction::make()->iconButton());
        if (PrivilegeController::privilege_check($menu_ids, 8))
            array_push($actions, DeleteAction::make()->iconButton());
        return $actions;
    }
}
