<?php

namespace App\Filament\Resources\ItemReceiptResource\RelationManagers;

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
use App\Models\FollowupOfficer;
use App\Models\WarehouseDetail;
use App\Models\ItemReceiptDetail;
use App\Models\ItemRequestDetail;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use App\Tables\Columns\SelectCheckbox;
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
                    }),
                TextInput::make('qty')->stripCharacters(',')->numeric()->required()->suffix(fn(Get $get) => Item::find($get('item_id'))->unit->name ?? ''),
                TextInput::make('notes')->maxLength(255),
                Hidden::make('unit_id'),
            ]);
    }

    public function table(Table $table): Table
    {
        $is_warehouse_visible = false;
        if (Auth::user()->privilege->id == 1) $is_warehouse_visible = true;
        if (@FollowupOfficer::where(['user_id' => Auth::user()->id, 'action' => 'item-receipt-approve'])->first()->id > 0) $is_warehouse_visible = true;
        if (@FollowupOfficer::where(['user_id' => Auth::user()->id, 'action' => 'stock-keeper'])->first()->id > 0) $is_warehouse_visible = true;

        return $table
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
                Tables\Columns\TextColumn::make('qty_outstanding')->alignRight()->label('Outstanding Qty')
                    ->default(function ($record) {
                        $po_detail = ItemReceiptDetail::find($record->id);
                        if ($po_detail) {
                            $item_receipts = ItemReceiptDetail::where('purchase_order_detail_id', $po_detail->purchase_order_detail_id)->get();
                            $settled_qty = 0;
                            foreach ($item_receipts as $receipt) {
                                if ($receipt->item_id == $po_detail->item_id) {
                                    $settled_qty += $receipt->qty;
                                }
                            }
                            return $po_detail->qty_po - $settled_qty;
                        }
                        return 0;
                    }),
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
                Tables\Actions\CreateAction::make()->label('Add Item')->icon('heroicon-o-plus')
                    ->after(function (ItemReceiptDetail $detail, Component $livewire) {
                        $detail->update([
                            'seqno' => $this->getOwnerRecord()->details()->max('seqno') + 1,
                            'unit_id' => $detail->item->unit_id
                        ]);
                        $livewire->dispatch('refreshItemReceipt');
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->iconButton()->after(function (Component $livewire) {
                    $livewire->dispatch('refreshItemReceipt');
                }),
                Tables\Actions\DeleteAction::make()->iconButton()->after(function (Component $livewire) {
                    $livewire->dispatch('refreshItemReceipt');
                }),
            ], ActionsPosition::BeforeColumns)
            ->paginated(false);
    }
}
