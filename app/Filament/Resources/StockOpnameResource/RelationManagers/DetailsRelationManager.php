<?php

namespace App\Filament\Resources\StockOpnameResource\RelationManagers;

use Dom\Text;
use Filament\Forms;
use App\Models\Item;
use App\Models\Unit;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Livewire\Component;
use Filament\Forms\Form;
use App\Models\ItemStock;
use Filament\Tables\Table;
use App\Models\WarehouseDetail;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\ActionsPosition;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class DetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'details';
    protected static ?string $title = 'Detail Items';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('item_id')->label('Item')
                    ->searchable()->preload()->required()->live()
                    ->options(function () {
                        return Item::all()->mapWithKeys(function ($item) {
                            return [$item->id => "[{$item->code}] -- {$item->name}"];
                        });
                    })
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        $set('unit_id', @Item::find($get('item_id'))->unit_id);
                        $set('qty', ItemStock::where('item_id', $get('item_id'))->first()->qty ?? 0);
                    }),
                TextInput::make('qty')->stripCharacters(',')->numeric()->readOnly()->suffix(fn(Get $get) => Item::find($get('item_id'))->unit->name ?? ''),
                TextInput::make('actual_qty')->stripCharacters(',')->numeric()->required()->label('Actual Qty')->suffix(fn(Get $get) => Item::find($get('item_id'))->unit->name ?? '')
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        $set('qty', ItemStock::where('item_id', $get('item_id'))->first()->qty ?? 0);
                    }),
                TextInput::make('notes')->maxLength(255),
                Hidden::make('unit_id'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')->label('No.')->rowIndex(),
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
                })->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Add Item')->icon('heroicon-o-plus'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->iconButton()->after(function (Component $livewire) {
                    $livewire->dispatch('refreshItemReceipt');
                }),
                Tables\Actions\DeleteAction::make()->iconButton(),
            ], ActionsPosition::BeforeColumns)->paginated(false);
    }
}
