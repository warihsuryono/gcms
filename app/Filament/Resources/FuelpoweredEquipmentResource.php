<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Traits\FilamentListActions;
use App\Models\FuelpoweredEquipment;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\ActionsPosition;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\FuelpoweredEquipmentResource\Pages;
use App\Filament\Resources\FuelpoweredEquipmentResource\RelationManagers;

class FuelpoweredEquipmentResource extends Resource
{
    use FilamentListActions;
    protected static ?string $model = FuelpoweredEquipment::class;
    protected static ?string $routename = 'fuelpowered-equipments';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('item_type_id')->relationship('item_type', 'name', fn(Builder $query) => $query->where('id', '<', 3))->required()->default(1)->label('Fuel Type'),
                Forms\Components\TextInput::make('name')->required()->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('item_type.name'),
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
            'index' => Pages\ListFuelpoweredEquipment::route('/'),
            // 'create' => Pages\CreateFuelpoweredEquipment::route('/create'),
            // 'edit' => Pages\EditFuelpoweredEquipment::route('/{record}/edit'),
        ];
    }
}
