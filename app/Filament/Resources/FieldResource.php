<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Field;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Traits\FilamentListActions;
use Filament\Tables\Enums\ActionsPosition;
use App\Filament\Resources\FieldResource\Pages;

class FieldResource extends Resource
{
    use FilamentListActions;
    protected static ?string $model = Field::class;
    protected static ?string $routename = 'fields';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->maxLength(255),
                Forms\Components\TextInput::make('lon')->maxLength(255),
                Forms\Components\TextInput::make('lat')->maxLength(255),
                Forms\Components\TextInput::make('area')->numeric()->default(0)->suffix('m²'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('longitude')->label('Longitude'),
                Tables\Columns\TextColumn::make('lat')->label('Latitude'),
                Tables\Columns\TextColumn::make('area')->numeric()->suffix('m²'),
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
            'index' => Pages\ListFields::route('/'),
            'create' => Pages\CreateField::route('/create'),
            'edit' => Pages\EditField::route('/{record}/edit'),
        ];
    }
}
