<?php

namespace App\Filament\Resources\StockOpnameResource\RelationManagers;

use Filament\Forms;
use App\Models\Item;
use App\Models\Unit;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\WarehouseDetail;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\ActionsPosition;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class DetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'details';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('item_id')
                    ->searchable()->preload()->required()->live()
                    ->options(function () {
                        return Item::all()->mapWithKeys(function ($item) {
                            return [$item->id => "[{$item->code}] -- {$item->name}"];
                        });
                    })
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        $set('unit_id', @Item::find($get('item_id'))->unit_id);
                    }),
                TextInput::make('qty')->stripCharacters(',')->numeric()->required(),
                TextInput::make('actual_qty')->stripCharacters(',')->numeric()->required(),
                Select::make('unit_id')->options(Unit::all()->pluck('name', 'id'))->relationship('unit', 'name')->disabled(),
                TextInput::make('notes')->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('item_id')
            ->columns([
                Tables\Columns\TextColumn::make('item_id')->label('Item')->formatStateUsing(fn($state) => "[" . Item::find($state)->code . "] -- " . Item::find($state)->name),
                Tables\Columns\TextColumn::make('qty')->alignRight(),
                Tables\Columns\TextColumn::make('actual_qty')->alignRight()->label('Actual Qty'),
                Tables\Columns\TextColumn::make('unit.name'),
                Tables\Columns\TextColumn::make('notes'),
                Tables\Columns\TextColumn::make('warehouse_detail_ids')->label('Storage Locations')->default(function ($record) {
                    $warehouse_detail_ids = json_decode(Item::find($record->item_id)->warehouse_detail_ids);
                    if ($warehouse_detail_ids) {
                        $warehouse_details = "";
                        foreach ($warehouse_detail_ids as $warehouse_detail_id) {
                            $warehouse_details .= WarehouseDetail::find($warehouse_detail_id)->code . ", ";
                        }
                        return substr($warehouse_details, 0, -2);
                    } else return '';
                }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->iconButton()->after(function (Component $livewire) {
                    $livewire->dispatch('refreshItemReceipt');
                }),
                Tables\Actions\DeleteAction::make()->iconButton(),
            ], ActionsPosition::BeforeColumns)->paginated(false);
    }
}
