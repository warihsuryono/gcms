<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FuelConsumptionResource\Pages;
use App\Filament\Resources\FuelConsumptionResource\RelationManagers;
use App\Models\FuelConsumption;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FuelConsumptionResource extends Resource
{
    protected static ?string $model = FuelConsumption::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('consumption_at'),
                Forms\Components\Select::make('item_type_id')
                    ->relationship('item_type', 'name')
                    ->default(0),
                Forms\Components\TextInput::make('quantity')
                    ->numeric()
                    ->default(0),
                Forms\Components\Select::make('unit_id')
                    ->relationship('unit', 'name')
                    ->default(0),
                Forms\Components\TextInput::make('deleted_by')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('created_by')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('updated_by')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('consumption_at')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('item_type.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('unit.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_by')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_by')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_by')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListFuelConsumptions::route('/'),
            'create' => Pages\CreateFuelConsumption::route('/create'),
            'edit' => Pages\EditFuelConsumption::route('/{record}/edit'),
        ];
    }
}
