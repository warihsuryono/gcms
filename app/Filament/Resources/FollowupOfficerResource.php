<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\FollowupOfficer;
use Filament\Resources\Resource;
use App\Filament\Resources\FollowupOfficerResource\Pages;
use App\Traits\FilamentListActions;
use Filament\Tables\Enums\ActionsPosition;

class FollowupOfficerResource extends Resource
{
    use FilamentListActions;
    protected static ?string $model = FollowupOfficer::class;
    protected static ?string $routename = 'followup-officers';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('action')->maxLength(255),
                Forms\Components\Select::make('user_id')->relationship('user', 'name')->searchable()->preload()->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('action')->searchable(),
                Tables\Columns\TextColumn::make('user.name')->numeric()->sortable(),
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
            'index' => Pages\ListFollowupOfficers::route('/'),
            'create' => Pages\CreateFollowupOfficer::route('/create'),
            'edit' => Pages\EditFollowupOfficer::route('/{record}/edit'),
        ];
    }
}
