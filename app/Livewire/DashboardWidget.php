<?php

namespace App\Livewire;

use App\Models\Attendance;
use App\Models\AttendanceTarget;
use App\Models\BusinessTrip;
use App\Models\Leave;
use App\Models\Reimbursement;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class DashboardWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '1s';
    protected function getStats(): array
    {
        $attendance_target = AttendanceTarget::where('start_at', '<=', date('Y-m-d'))->where('end_at', '>=', date('Y-m-d'))->first();
        $cutoffdate = explode("-", $attendance_target->end_at ?? '0000-00-00')[2] ?? 25;
        if (empty(Auth::user()->employee)) return [];
        $today = 0;
        $remain_today = 0;
        $firstday_week = date('Y-m-d', strtotime("monday this week"));
        $lastday_week = date('Y-m-d', strtotime("sunday this week"));
        if (date("d") > $cutoffdate) {
            $firstday_month = date('Y-m-d 00:00:00', mktime(0, 0, 0, date("m"), $cutoffdate + 1));
            $lastday_month = date('Y-m-d 23:59:59', mktime(0, 0, 0, date("m") + 1, $cutoffdate));
        } else {
            $firstday_month = date('Y-m-d 00:00:00', mktime(0, 0, 0, date("m") - 1, $cutoffdate + 1));
            $lastday_month = date('Y-m-d 23:59:59', mktime(0, 0, 0, date("m"), $cutoffdate));
        }

        $weekattendanceCount = Attendance::where('user_id', Auth::user()->id)->whereBetween('tap_in', [$firstday_week, $lastday_week])->sum('day_minutes');
        // $monthattendanceCount = Attendance::where('user_id', Auth::user()->id)->whereLike('tap_in', date('Y-m-') . '%')->sum('day_minutes');
        $monthattendanceCount = Attendance::where('user_id', Auth::user()->id)->whereBetween('tap_in', [$firstday_month, $lastday_month])->sum('day_minutes');
        $reimbursementCount = is_officer(Auth::user()->id, "reimbursement-%") ? Reimbursement::count() : Reimbursement::where('user_id', Auth::user()->id)->count();
        $businessTripCount = is_officer(Auth::user()->id, "businesstrip-%") ? BusinessTrip::count() : BusinessTrip::where('created_by', Auth::user()->id)->count();
        $leaveCount = is_officer(Auth::user()->id, "leave-%") ? Leave::count() : Leave::where('created_by', Auth::user()->id)->count();
        if (Attendance::where('user_id', Auth::user()->id)->whereLike('tap_in', date('Y-m-d') . '%')->whereNull('tap_out')->exists()) {
            $tap_in = Attendance::where('user_id', Auth::user()->id)->whereLike('tap_in', date('Y-m-d') . '%')->whereNull('tap_out')->first()->tap_in;
            $now = substr($tap_in, 0, 10) . " " . date("H:i:s");
            if ($now > substr($tap_in, 0, 10) . " 21:00:00") $now = substr($tap_in, 0, 10) . " 21:00:00";
            $weekattendanceCount += floor(abs(strtotime($now) - strtotime($tap_in)) / 60);
            $diff_today = strtotime($now) - strtotime($tap_in);
            $remain_today = (8 * 3600) - $diff_today;
            if (date("H") > 13) {
                $diff_today = $diff_today - 3600;
                $remain_today = $remain_today + 3600;
                $weekattendanceCount -= 60;
            }

            $today_h = intdiv($diff_today, 3600);
            $today_m = intdiv(($diff_today - ($today_h * 3600)), 60);
            $today_s = ($diff_today - ($today_h * 3600)) % 60;
            $today = $today_h . "h " . $today_m . "m " . $today_s . "s ";

            $remain_h = intdiv($remain_today, 3600);
            $remain_m = intdiv(($remain_today - ($remain_h * 3600)), 60);
            $remain_s = ($remain_today - ($remain_h * 3600)) % 60;
            $remain_today = $remain_h . "h " . $remain_m . "m " . $remain_s . "s ";
        }

        $inHourWeekAttendance = intdiv($weekattendanceCount, 60) . "h " . ($weekattendanceCount % 60) . "m";
        $remainingMinutes = 1440 - $weekattendanceCount;
        return [
            Stat::make('Today Attendance', $today)
                ->icon('heroicon-m-building-office-2')
                ->description("Remaining : " . $remain_today)
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                    'wire:click' => 'goToAttendance',
                ]),
            Stat::make('Attendance this week', "$inHourWeekAttendance / 24h")
                ->icon('heroicon-m-building-office-2')
                ->description("Remaining : " . intdiv($remainingMinutes, 60) . "h " . ($remainingMinutes % 60) . "m")
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                    'wire:click' => 'goToAttendance',
                ]),
            Stat::make('Attendance this month', floor($monthattendanceCount / 60) . " / " . ($attendance_target->target ?? 0) . " hour(s)")
                ->icon('heroicon-m-building-office-2')
                ->description("Cut Off Date : " . $cutoffdate)
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                    'wire:click' => 'goToAttendance',
                ]),
            Stat::make('Reimbursements', $reimbursementCount)
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                    'wire:click' => 'goToReimbursements',
                ]),
            Stat::make('Business Trip', $businessTripCount)
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                    'wire:click' => 'goToBusinessTrip',
                ]),
            Stat::make('Leaves', $leaveCount)
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                    'wire:click' => 'goToLeaves',
                ]),
        ];
    }

    public function goToAttendance()
    {
        return redirect()->route('filament.' . env('PANEL_PATH') . '.resources.attendances.tap');
    }

    public function goToReimbursements()
    {
        return redirect()->route('filament.' . env('PANEL_PATH') . '.resources.reimbursements.index');
    }

    public function goToBusinessTrip()
    {
        return redirect()->route('filament.' . env('PANEL_PATH') . '.resources.business-trips.index');
    }

    public function goToLeaves()
    {
        return redirect()->route('filament.' . env('PANEL_PATH') . '.resources.leaves.index');
    }
}
