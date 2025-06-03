<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\MarriageStatus;
use Filament\Resources\Resource;
use Filament\Tables\Enums\ActionsPosition;
use App\Filament\Resources\MarriageStatusResource\Pages;
use App\Traits\FilamentListActions;

class MarriageStatusResource extends Resource
{
    use FilamentListActions;
    protected static ?string $model = MarriageStatus::class;
    protected static ?string $routename = 'marriage-statuses';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->maxLength(100)->required(),
                Forms\Components\TextInput::make('description')->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('description')->searchable(),
            ])
            ->paginated(false)
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
            'index' => Pages\ListMarriageStatuses::route('/'),
            'create' => Pages\CreateMarriageStatus::route('/create'),
            'edit' => Pages\EditMarriageStatus::route('/{record}/edit'),
        ];
    }
}
