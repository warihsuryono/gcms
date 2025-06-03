<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\EmployeeStatus;
use Filament\Resources\Resource;
use Filament\Tables\Enums\ActionsPosition;
use App\Filament\Resources\EmployeeStatusResource\Pages;
use App\Traits\FilamentListActions;

class EmployeeStatusResource extends Resource
{
    use FilamentListActions;
    protected static ?string $model = EmployeeStatus::class;
    protected static ?string $routename = 'employee-statuses';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->maxLength(100)->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
            ])
            ->paginated(false)
            ->actions(self::actions(self::$routename), ActionsPosition::BeforeColumns);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployeeStatuses::route('/'),
            'create' => Pages\CreateEmployeeStatus::route('/create'),
            'edit' => Pages\EditEmployeeStatus::route('/{record}/edit'),
        ];
    }
}
