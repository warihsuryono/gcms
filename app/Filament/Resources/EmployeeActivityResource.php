<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\EmployeeActivity;
use Filament\Resources\Resource;
use App\Traits\FilamentListActions;
use Filament\Tables\Enums\ActionsPosition;
use App\Filament\Resources\EmployeeActivityResource\Pages;

class EmployeeActivityResource extends Resource
{
    use FilamentListActions;
    protected static ?string $model = EmployeeActivity::class;
    protected static ?string $routename = 'employee-activities';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
            ])
            ->filters([
                //
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
            'index' => Pages\ListEmployeeActivities::route('/'),
            'create' => Pages\CreateEmployeeActivity::route('/create'),
            'edit' => Pages\EditEmployeeActivity::route('/{record}/edit'),
        ];
    }
}
