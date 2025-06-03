<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\AttendanceTarget;
use Filament\Resources\Resource;
use App\Traits\FilamentListActions;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\ActionsPosition;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AttendanceTargetResource\Pages;
use App\Filament\Resources\AttendanceTargetResource\RelationManagers;

class AttendanceTargetResource extends Resource
{
    use FilamentListActions;
    protected static ?string $model = AttendanceTarget::class;

    protected static ?string $routename = 'attendance-targets';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('start_at')->default(date('Y-m-26')),
                Forms\Components\DatePicker::make('end_at')->default(date('Y-m-25', strtotime('next month'))),
                Forms\Components\TextInput::make('target')->numeric()->default(96)->suffix('Hour(s)'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('start_at')->date(),
                Tables\Columns\TextColumn::make('end_at')->date(),
                Tables\Columns\TextColumn::make('target')->suffix(' Hour(s)'),
            ])
            ->filters([
                Filter::make('date')
                    ->form([DatePicker::make('start_at'), DatePicker::make('end_at')])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['start_at'], fn(Builder $query, $date): Builder => $query->where('start_at', $date))
                            ->when($data['end_at'], fn(Builder $query, $date): Builder => $query->where('end_at', $date));
                    }),
            ])
            ->actions(self::actions(self::$routename), ActionsPosition::BeforeColumns);
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
            'index' => Pages\ListAttendanceTargets::route('/'),
            // 'create' => Pages\CreateAttendanceTarget::route('/create'),
            // 'edit' => Pages\EditAttendanceTarget::route('/{record}/edit'),
        ];
    }
}
