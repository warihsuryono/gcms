<?php

namespace App\Filament\Resources\WarehouseResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\ActionsPosition;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class DetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'details';

    protected static ?string $title = 'Storage Locations';

    public static function getCreateFormTitle(): string
    {
        return 'Tambah Pemesanan Baru';
    }

    public function getEditFormTitle(): string
    {
        return 'Edit Pemesanan';
    }

    public function getViewFormTitle(): string
    {
        return 'Detail Pemesanan';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')->required()->maxLength(255),
                Forms\Components\TextInput::make('aisle')->required()->maxLength(255),
                Forms\Components\TextInput::make('rack')->required()->maxLength(255),
                Forms\Components\TextInput::make('level')->required()->maxLength(255),
                Forms\Components\TextInput::make('slot')->required()->maxLength(255),
                Forms\Components\TextInput::make('notes')->required()->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('details')
            ->columns([
                Tables\Columns\TextColumn::make('code'),
                Tables\Columns\TextColumn::make('aisle'),
                Tables\Columns\TextColumn::make('rack'),
                Tables\Columns\TextColumn::make('level'),
                Tables\Columns\TextColumn::make('slot'),
                Tables\Columns\TextColumn::make('notes'),
            ])
            ->filters([
                //
            ])
            ->headerActions([Tables\Actions\CreateAction::make()->label('New Storage Location')->modalHeading('New Storage Location')])
            ->actions([Tables\Actions\EditAction::make()->iconButton()->modalHeading('Delete Storage Location'), Tables\Actions\DeleteAction::make()->iconButton()->modalHeading('Delete Storage Location')], ActionsPosition::BeforeColumns)
            ->paginated(false);
    }
}
