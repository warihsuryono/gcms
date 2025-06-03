<?php

namespace App\Http\Controllers;

use App\Models\menu;
use App\Models\Privilege;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PrivilegeController extends Controller
{
    static function get_menu_ids($url)
    {
        $return = [];
        foreach (menu::where(["deleted_by" => "", "url" => $url])->get() as $menus) {
            $return[] = $menus->id;
        }
        return $return;
    }

    static function privilege_check($menu_ids, $mode = "0")
    {
        //$mode 0 ==> list
        //$mode 1 ==> add
        //$mode 2 ==> edit
        //$mode 4 ==> view
        //$mode 8 ==> delete
        $allowed = false;
        if (Auth::user()->privilege_id == 1) $allowed = true;
        else {
            $grouplogin = Privilege::where("id", Auth::user()->privilege_id)->first();
            if ($grouplogin) {
                $_menu_ids = explode(",", $grouplogin->menu_ids);
                $_privileges = explode(",", $grouplogin->privileges);
                $privileges = [];

                foreach ($_privileges as $key => $privilege) {
                    if (array_key_exists($key, $_menu_ids)) $privileges[$_menu_ids[$key]] = $privilege;
                }

                if (count($privileges) > 0 && count($menu_ids) > 0)
                    foreach ($menu_ids as $menu_id) {
                        if ($mode == "0") {
                            if (isset($privileges[$menu_id])) $allowed = true;
                        } else {
                            if (isset($privileges[$menu_id]) && ($privileges[$menu_id] & $mode)) $allowed = true;
                        }
                    }
            }
        }

        if (!$allowed) {
            Session::flash('flash-message', 'Sorry, you don`t have the privilege!');
            Session::flash('flash-type', 'danger');
        }
        return $allowed;
    }
}
