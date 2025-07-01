<?php

namespace App\Filament\Resources\ItemReceiptResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\FollowupOfficer;
use App\Models\Item;
use App\Models\ItemReceiptDetail;
use App\Models\Unit;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Livewire\Component;
use App\Models\ItemStock;
use App\Models\WarehouseDetail;
use App\Models\ItemRequestDetail;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use App\Tables\Columns\SelectCheckbox;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Enums\ActionsPosition;

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
                Select::make('unit_id')->options(Unit::all()->pluck('name', 'id'))->relationship('unit', 'name')->disabled(),
                TextInput::make('notes')->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        $is_warehouse_visible = false;
        if (Auth::user()->privilege->id == 1) $is_warehouse_visible = true;
        if (@FollowupOfficer::where(['user_id' => Auth::user()->id, 'action' => 'item-receipt-approve'])->first()->id > 0) $is_warehouse_visible = true;
        if (@FollowupOfficer::where(['user_id' => Auth::user()->id, 'action' => 'stock-keeper'])->first()->id > 0) $is_warehouse_visible = true;

        return $table
            ->recordTitleAttribute('item_id')
            ->columns([
                Tables\Columns\TextColumn::make('item_id')->label('Item')
                    ->formatStateUsing(fn($state) => "[" . Item::find($state)->code . "] -- " . Item::find($state)->name),
                Tables\Columns\TextColumn::make('qty_po')->alignRight()->label('PO Qty')
                    ->default(function ($record) {
                        $po_detail = ItemReceiptDetail::find($record->id);
                        if ($po_detail) return $po_detail->qty_po;
                        return 0;
                    }),
                Tables\Columns\TextColumn::make('qty')->alignRight(),
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
                })->visible($is_warehouse_visible)->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->after(function (ItemReceiptDetail $detail, Component $livewire) {
                        $detail->update([
                            'seqno' => $this->getOwnerRecord()->details()->max('seqno') + 1,
                            'unit_id' => $detail->item->unit_id
                        ]);
                        $livewire->dispatch('refreshItemReceipt');
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->iconButton()->after(function (Component $livewire, $record) {
                    $record->update(['qty_outstanding' => $record->qty_po - $record->qty]);
                    $livewire->dispatch('refreshItemReceipt');
                }),
                Tables\Actions\DeleteAction::make()->iconButton()->after(function (Component $livewire) {
                    $livewire->dispatch('refreshItemReceipt');
                }),
            ], ActionsPosition::BeforeColumns)
            ->paginated(false);
    }
}
