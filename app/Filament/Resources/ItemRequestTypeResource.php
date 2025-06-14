<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ItemRequestType;
use Filament\Resources\Resource;
use App\Traits\FilamentListActions;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\ActionsPosition;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ItemRequestTypeResource\Pages;
use App\Filament\Resources\ItemRequestTypeResource\RelationManagers;

class ItemRequestTypeResource extends Resource
{
    use FilamentListActions;
    protected static ?string $model = ItemRequestType::class;
    protected static ?string $routename = 'item-request-types';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required()->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
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
            'index' => Pages\ListItemRequestTypes::route('/'),
            'create' => Pages\CreateItemRequestType::route('/create'),
            'edit' => Pages\EditItemRequestType::route('/{record}/edit'),
        ];
    }
}
