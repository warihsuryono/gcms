<?php

namespace App\Livewire;

use Filament\Notifications\Notification;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileWidget extends Widget
{

    protected static string $view = 'livewire.profile-widget';
    protected int | string | array $columnSpan = 'full';
    protected $name, $avatar, $is_guest = false, $is_employee = false;

    public function mount()
    {
        if (Auth::user()->privilege_id == 7) $this->is_guest = true;
        if (Auth::user()->employee) $this->is_employee = true;

        if (Auth::user()->photo && Storage::exists("public/" . Auth::user()->photo) && config('app.env') == 'local')
            $this->avatar = Storage::url(Auth::user()->photo);
        else if (Auth::user()->photo) $this->avatar = url("storage/" . Auth::user()->photo);
        else $this->avatar = "https://ui-avatars.com/api/?name=" . Auth::user()->name . "&color=FFFFFF&background=09090b";
    }

    public function goToProfile()
    {
        return redirect()->route('filament.' . env('PANEL_PATH') . '.resources.profiles.edit', Auth::user()->id);
    }

    public function goToTapAttendance()
    {
        return redirect()->route('filament.' . env('PANEL_PATH') . '.resources.attendances.tap');
    }

    public function goToPresenceSchedules()
    {
        return redirect()->route('filament.' . env('PANEL_PATH') . '.resources.presence-schedules.index');
    }

    public function goToNewUrgentWorkOrder()
    {
        return redirect()->route('filament.' . env('PANEL_PATH') . '.resources.urgent-work-orders.new');
    }
}
