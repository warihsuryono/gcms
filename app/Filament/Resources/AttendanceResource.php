<?php

namespace App\Filament\Resources;

use App\Filament\Exports\AttendanceExporter;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Attendance;
use Filament\Tables\Table;
use App\Models\FollowupOfficer;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AttendanceResource\Pages;
use App\Filament\Resources\AttendanceResource\RelationManagers;
use Filament\Forms\Components\DatePicker;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Columns\Summarizers\Average;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Grouping\Group;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function canCreate():bool{
        return !empty(Auth::user()->employee);
    }
    public static function canAccess():bool{
        return !empty(Auth::user()->employee);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('tap_in')->dateTime()->label('Tap In'),
                TextEntry::make('tap_out')->dateTime()->label('Tap Out'),
                ImageEntry::make('photo_in'),
                ImageEntry::make('photo_out'),
                TextEntry::make('lat_in')
                    ->label('Location In')->formatStateUsing(function ($record) {
                        return "{$record->lat_in},{$record->lon_in}" ?? "Not Set";
                    })->url(function ($record) {
                        return "https://www.google.com/maps/search/?api=1&query={$record->lat_in},{$record->lon_in}";
                    })->openUrlInNewTab(),
                TextEntry::make('lat_out')
                    ->label('Location Out')
                    ->formatStateUsing(function ($record) {
                        return "{$record->lat_out},{$record->lon_out}";
                    })->url(function ($record) {
                        return "https://www.google.com/maps/search/?api=1&query={$record->lat_out},{$record->lon_out}";
                    })->openUrlInNewTab(),
                TextEntry::make('day_minutes')
                    ->label("Total hour")
                    ->formatStateUsing(function ($record) {
                        $total_minutes = $record->day_minutes;
                        $hours = intdiv($total_minutes, 60);
                        $minutes = $total_minutes % 60;
                        if ($hours == 0) {
                            $text = "{$minutes} minutes (Total: $total_minutes minutes)";
                            return $text;
                        }
                        $text = "{$hours} hours {$minutes} minutes (Total : $total_minutes minutes)";
                        return $text;
                    }),

            ]);
    }

    public static function table(Table $table): Table
    {
        if (
            Auth::user()->id > 1 //not superuser
            && !FollowupOfficer::where('action', 'presence-schedule-admin')->where('user_id', Auth::user()->id)->first() //not followup officer
            && (Auth::user()->employee && Auth::user()->employee->leader_user_id > 0) //not board of directors
        )
            $filters = [];
        else
            $filters = [
                SelectFilter::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
                Filter::make("created_at")
                    ->label('Date Range')
                    ->form([
                        'date_start' => DatePicker::make('date_start')->label('Start Date'),
                        'date_end' => DatePicker::make('date_end')->label('End Date'),
                    ])
                    ->query(function ($query, $data) use ($table) {
                        $whereRaw = "1=1";
                        if (isset($data['date_start'])) {
                            $whereRaw .= " AND created_at >= '" . $data['date_start'] . "'";
                        }
                        if (isset($data['date_end'])) {
                            $whereRaw .= " AND created_at <= '" . $data['date_end'] . "'";
                        }

                        return $query->whereRaw($whereRaw);
                    }),
            ];

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name'),
                ImageColumn::make('photo_in'),
                ImageColumn::make('photo_out'),
                Tables\Columns\TextColumn::make('tap_in')->dateTime()->label('Tap In'),
                Tables\Columns\TextColumn::make('tap_out')->dateTime()->label('Tap Out'),
                Tables\Columns\TextColumn::make('day_minutes')
                    ->label("Work Hour")
                    ->formatStateUsing(function ($state) {
                        $h = intdiv($state, 60);
                        $m = $state % 60;
                        if ($h == 0) {
                            return "{$m}m";
                        }
                        return "{$h}h {$m}m";
                    })->summarize([
                        Sum::make()
                            ->label('Total Work Hour')
                            ->formatStateUsing(function ($state) {
                                $h = intdiv($state, 60);
                                $m = $state % 60;
                                if ($h == 0) {
                                    return "{$m}m";
                                }
                                return "{$h}h {$m}m";
                            }),
                        Average::make()->label('Avg. Work Hour')
                            ->formatStateUsing(function ($state) {
                                $h = intdiv($state, 60);
                                $m = $state % 60;
                                if ($h == 0) {
                                    return "{$m}m";
                                }
                                return "{$h}h {$m}m";
                            }),
                    ]),
            ])
            ->filters($filters)
            ->modifyQueryUsing(function (Builder $query) {
                if (
                    Auth::user()->id > 1 //not superuser
                    && !FollowupOfficer::where('action', 'presence-schedule-admin')->where('user_id', Auth::user()->id)->first() //not followup officer
                    && ((Auth::user()->employee && Auth::user()->employee->leader_user_id > 0) || empty(Auth::user()->employee)) //not board of directors
                ) { //show only creator
                    $query->where('user_id', Auth::user()->id);
                } else {
                    $query;
                }
                return $query->orderBy('id', 'DESC');
            })

            ->groups([
                Group::make("user.name")
                    ->label("User")
                    ->groupQueryUsing(fn($query) => $query->groupBy('user_id'))
            ])
            ->defaultSort("user_id", "asc")
            ->defaultGroup('user.name');
            
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'tap' => Pages\TapAttendance::route('/tap'),
            'view' => Pages\ViewAttendance::route('/{record}'),
        ];
    }
}
