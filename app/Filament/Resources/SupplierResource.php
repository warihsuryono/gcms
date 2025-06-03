<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Supplier;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Traits\FilamentListActions;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Enums\ActionsPosition;
use App\Filament\Resources\SupplierResource\Pages;

class SupplierResource extends Resource
{
    use FilamentListActions;
    protected static ?string $model = Supplier::class;
    protected static ?string $routename = 'suppliers';


    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('import_domestic')
                    ->options(['import' => 'Import', 'domestic' => 'Domestic', 'both' => 'Both'])->required(),
                Forms\Components\TextInput::make('name')->maxLength(100),
                Forms\Components\TextInput::make('pic')->maxLength(50),
                Forms\Components\TextInput::make('pic_phone')->tel()->maxLength(30),
                Forms\Components\TextInput::make('email')->email()->maxLength(100),
                Forms\Components\TextInput::make('address')->maxLength(255),
                Forms\Components\Select::make('city_id')->relationship('city', 'name')->searchable()->preload()->default(0),
                Forms\Components\Select::make('province_id')->relationship('province', 'name')->searchable()->preload()->default(0),
                Forms\Components\TextInput::make('country')->maxLength(100)->default(''),
                Forms\Components\TextInput::make('zipcode')->maxLength(10)->default(''),
                Forms\Components\TextInput::make('fax')->maxLength(30)->default(''),
                Forms\Components\TextInput::make('nationality')->maxLength(100)->default(''),
                Forms\Components\TextInput::make('remarks')->maxLength(255)->default(''),
                Forms\Components\TextInput::make('npwp')->maxLength(50)->default(''),
                Forms\Components\TextInput::make('nppkp')->maxLength(50)->default(''),
                Forms\Components\TextInput::make('tax_invoice_no')->maxLength(50)->default(''),
                Forms\Components\Select::make('payment_type_id')->relationship('payment_type', 'name')->searchable()->preload()->default(0),
                Forms\Components\Select::make('bank_id')->relationship('bank', 'name')->searchable()->preload()->default(0),
                Forms\Components\TextInput::make('bank_account_name')->maxLength(255),
                Forms\Components\TextInput::make('bank_account_no')->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('import_domestic')->label('Import/Domestic')->state(fn(Supplier $record) => ucwords($record->import_domestic)),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('pic')->searchable(),
                Tables\Columns\TextColumn::make('pic_phone')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('address')->toggleable(isToggledHiddenByDefault: true)->searchable(),
                Tables\Columns\TextColumn::make('city.name'),
                Tables\Columns\TextColumn::make('province.name')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('country'),
                Tables\Columns\TextColumn::make('zipcode')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('fax')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('nationality')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('remarks')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('npwp')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('nppkp')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('tax_invoice_no')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('payment_type.name')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('bank.name')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('bank_account_name')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('bank_account_no')->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('import_domestic')->label('Import/Domestic')->options(['import' => 'Import', 'domestic' => 'Domestic', 'both' => 'Both']),
                SelectFilter::make('city_id')->relationship('city', 'name')->searchable()->preload(),
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
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'edit' => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }
}
