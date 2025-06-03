<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\ItemSpecification;
use App\Traits\FilamentListActions;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\ActionsPosition;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ItemSpecificationResource\Pages;
use App\Filament\Resources\ItemSpecificationResource\RelationManagers;

class ItemSpecificationResource extends Resource
{
    use FilamentListActions;
    protected static ?string $model = ItemSpecification::class;
    protected static ?string $routename = 'item-specifications';


    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->maxLength(50),
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
            'index' => Pages\ListItemSpecifications::route('/'),
            'create' => Pages\CreateItemSpecification::route('/create'),
            'edit' => Pages\EditItemSpecification::route('/{record}/edit'),
        ];
    }
}
