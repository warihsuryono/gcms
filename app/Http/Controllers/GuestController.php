<?php

namespace App\Http\Controllers;

use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class GuestController extends Controller
{
    public function visit($redirect = "")
    {
        Session::put('guest_redirect', $redirect);
        Session::put('is_guest_link', 1);
        return redirect(env('PANEL_PATH') . '/' . $redirect);
    }

    public function continue_as_guest()
    {
        try {
            try {
                User::whereNotIn('id', DB::table('sessions')->whereNotNull('user_id')->get()->pluck('user_id'))->where('privilege_id', 7)->forceDelete();
            } catch (\Exception $e) {
            }
            if (!Auth::user()) {
                $name = 'guest' . date("hsimdy") . rand(0, 9) . rand(0, 9) . rand(0, 9);
                $email = $name . '@gws.co.id';
                $password = Hash::make('Guest123');
                $user = User::create(['privilege_id' => 7, 'email' => $email, 'name' => $name, 'password' => $password, 'msisdn' => '+628xxxxxxx']);
                Auth::login($user);
            }
            $guest_redirect = Session::get('guest_redirect');
            Session::remove('guest_redirect');
            Notification::make()
                ->title("Welcome to GWS Workspace")
                ->success()
                ->body("You are now logged in as a guest.")
                ->send();
            return redirect(env('PANEL_PATH') . '/' . $guest_redirect);
        } catch (\Exception $e) {
            Notification::make()
                ->title("Failed to continue as guest")
                ->danger()
                ->body($e->getMessage())
                ->send();
            return redirect(env('PANEL_PATH') . '/login');
        }
    }
}
