<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\PresenceSchedule;
use Filament\Resources\Resource;
use App\Traits\FilamentListActions;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\ActionsPosition;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PresenceScheduleResource\Pages;
use App\Filament\Resources\PresenceScheduleResource\RelationManagers;
use App\Models\FollowupOfficer;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Auth;

class PresenceScheduleResource extends Resource
{
    use FilamentListActions;
    protected static ?string $model = PresenceSchedule::class;
    protected static ?string $routename = 'presence-schedules';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        $forms = [];
        if (FollowupOfficer::where('action', 'presence-schedule-admin')->where('user_id', Auth::user()->id)->first())
            array_push($forms, Forms\Components\Select::make('user_id')->relationship('user', 'name')->searchable()->preload());

        array_push($forms, Forms\Components\Select::make('activity_id')->relationship('activity', 'name')->required()->searchable()->preload());
        array_push($forms, Forms\Components\DatePicker::make('presence_at')->label('Date'));
        array_push($forms, Forms\Components\TimePicker::make('hour_from'));
        array_push($forms, Forms\Components\TimePicker::make('hour_until'));
        array_push($forms, Forms\Components\TextInput::make('notes')->maxLength(255));


        return $form->schema($forms);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('User Name'),
                Tables\Columns\TextColumn::make('presence_at')->label('Date')->date('d-m-Y'),
                Tables\Columns\TextColumn::make('hour_from'),
                Tables\Columns\TextColumn::make('hour_until'),
                Tables\Columns\TextColumn::make('activity.name'),
                Tables\Columns\TextColumn::make('notes'),
            ])
            ->filters([
                SelectFilter::make('user_id')->relationship('user', 'name')->searchable()->preload(),
                Filter::make("presence_at")
                    ->label('Date Range')
                    ->form([
                        'date_start' => DatePicker::make('date_start')->label('Start Date'),
                        'date_end' => DatePicker::make('date_end')->label('End Date'),
                    ])
                    ->query(function ($query, $data) use ($table) {
                        $whereRaw = "1=1";
                        if (isset($data['date_start'])) {
                            $whereRaw .= " AND presence_at >= '" . $data['date_start'] . "'";
                        }
                        if (isset($data['date_end'])) {
                            $whereRaw .= " AND presence_at <= '" . $data['date_end'] . "'";
                        }

                        return $query->whereRaw($whereRaw);
                    }),
            ])
            ->modifyQueryUsing(function (Builder $query) {

                if (
                    Auth::user()->id > 1 //not superuser
                    && !FollowupOfficer::where('action', 'presence-schedule-admin')->where('user_id', Auth::user()->id)->first() //not followup officer
                    && (Auth::user()->employee && Auth::user()->employee->leader_user_id > 0) //not board of directors
                ) { //show only creator
                    $query->where('user_id', Auth::user()->id);
                    return $query;
                }
            })
            ->actions(self::actions(self::$routename), ActionsPosition::BeforeColumns)
            ->defaultSort('presence_at', 'desc');
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
            'index' => Pages\ListPresenceSchedules::route('/'),
            'create' => Pages\CreatePresenceSchedule::route('/create'),
            'edit' => Pages\EditPresenceSchedule::route('/{record}/edit'),
        ];
    }
}
