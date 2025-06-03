<?php

namespace App\Filament\Resources\AttendanceResource\Pages;

use Livewire\WithFileUploads;
use Filament\Resources\Pages\Page;
use Filament\Notifications\Notification;
use App\Filament\Resources\AttendanceResource;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TapAttendance extends Page
{
    use WithFileUploads;

    protected static string $resource = AttendanceResource::class;

    protected static string $view = 'filament.resources.attendance-resource.pages.tap-attendance';

    public function getViewData():array{
        return [
            "isInExists" => Attendance::where(['user_id' => Auth::user()->id])->whereLike('tap_in', date("Y-m-d ") . '%')->exists(),
        ];
    }

    public function callNotification($notification): void
    {
        Notification::make()->title($notification)->success()->send();
    }

    public function save(Request $request)
    {
        $request->validate(['photo' => 'required|image|mimes:jpeg,png,jpg,gif']);
        $photoName = Auth::user()->id . time() . date("YmdHis") . '.' . $request->photo->extension();
        $request->photo->storeAs('public/attendances', $photoName);
        if ($request->attendancetype == 'in') {
            $attendance = Attendance::where(['user_id' => Auth::user()->id])->where(function ($query) {
                $query->whereLike('tap_in', date("Y-m-d ") . '%')->orWhereLike('tap_out', date("Y-m-d ") . '%');
            })->first();

            if ($attendance) {
                Attendance::find($attendance->id)->update(
                    [
                        'photo_in' => 'attendances/' . $photoName,
                        'tap_in' => now(),
                        'lat_in' => $request->lat,
                        'lon_in' => $request->lon,
                        'photo_out' => '',
                        'tap_out' => Null,
                        'lat_out' => '',
                        'lon_out' => '',
                        'day_minutes' => 0
                    ]
                );
            } else {
                Attendance::create(
                    [
                        'user_id' => Auth::user()->id,
                        'photo_in' => 'attendances/' . $photoName,
                        'tap_in' => now(),
                        'lat_in' => $request->lat,
                        'lon_in' => $request->lon
                    ]
                );
            }
        } else {
            $attendance = Attendance::where(['user_id' => Auth::user()->id])->whereLike("tap_in", date("Y-m-d ") . "%")->first();
            if ($attendance) {
                $now = now();
                $day_minutes = abs(strtotime($attendance->tap_in) - strtotime($now)) / 60;

                // Penyesuaian jam kerja dikurangi jam istirahat (1 jam / 60 menit)
                $tapIn = Carbon::parse($attendance->tap_in);
                $tapOut = Carbon::parse($now); 

                $breakStart = Carbon::parse(date("Y-m-d 12:00:00"));
                $breakEnd = Carbon::parse(date("Y-m-d 13:00:00"));

                // tapIn less than equeal $breakStart and tapOut greather than equal $breakEnd
                if ($tapIn->lte($breakStart) && $tapOut->gte($breakEnd)) {
                    $day_minutes -= 60;
                }

                Attendance::find($attendance->id)->update(
                    [
                        'photo_out' => 'attendances/' . $photoName,
                        'tap_out' => now(),
                        'lat_out' => $request->lat,
                        'lon_out' => $request->lon,
                        'day_minutes' => $day_minutes
                    ]
                );
            } else {
                Attendance::create(
                    [
                        'user_id' => Auth::user()->id,
                        'photo_out' => 'attendances/' . $photoName,
                        'tap_out' => now(),
                        'lat_out' => $request->lat,
                        'lon_out' => $request->lon
                    ]
                );
            }
        }

        Notification::make()->title("Attendance Saved")->success()->send();

        return redirect(env('PANEL_PATH') . '/attendances');
    }
}