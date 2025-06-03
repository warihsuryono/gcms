<?php

namespace App\Http\Controllers;

use App\Models\menu;
use Illuminate\Http\Request;
use Filament\Notifications\Notification;

class MenuController extends Controller
{
    public function reorder($direction, menu $menu)
    {
        if (!PrivilegeController::privilege_check([9], 2)) {
            Notification::make()
                ->title('Sorry, you don`t have the privilege!')
                ->warning()
                ->send();
            return redirect(env('PANEL_PATH') . '/' . request()->segment(2) . "/");
        }
        $seqno = $menu->seqno;
        if ($direction == "moveup") {
            if ($seqno < 2)  return redirect(env('PANEL_PATH') . '/' . request()->segment(2) . "/");
            $new_seqno = $seqno - 1;
        }
        if ($direction == "movedown")
            $new_seqno = $seqno + 1;

        $parent_id = $menu->parent_id;
        $_menu = menu::where("parent_id", $parent_id)->where("seqno", $new_seqno)->first();
        $menu->update(["seqno" => $new_seqno]);
        if ($_menu) $_menu->update(["seqno" => $seqno]);
        return redirect(env('PANEL_PATH') . '/' . request()->segment(2) . "/");
    }
}
