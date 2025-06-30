<?php

namespace App\Filament\Resources\PurchaseOrderResource\RelationManagers;

use App\Models\Item;
use App\Models\Unit;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Support\RawJs;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\BulkAction;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\ActionsPosition;
use Illuminate\Database\Eloquent\Collection;
use Filament\Resources\RelationManagers\RelationManager;

class DetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'details';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('item_id')->searchable()->preload()->live()
                    ->options(function () {
                        return Item::all()->mapWithKeys(function ($item) {
                            return [$item->id => "[{$item->code}] -- {$item->name}"];
                        });
                    })
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        $set('unit_id', @Item::find($get('item_id'))->unit_id);
                    })
                    ->createOptionForm([
                        Select::make('item_specification_id')->relationship('item_specification', 'name')->searchable()->preload()->default(0),
                        Select::make('item_category_id')->relationship('item_category', 'name')->searchable()->preload()->default(0),
                        Select::make('item_type_id')->relationship('item_type', 'name')->searchable()->preload()->default(0),
                        Select::make('item_brand_id')->relationship('item_brand', 'name')->searchable()->preload()->default(0),
                        TextInput::make('name')->maxLength(255),
                        Select::make('unit_id')->relationship('unit', 'name')->searchable()->preload()->default(0),
                        Textarea::make('description')->columnSpanFull(),
                        TextInput::make('minimum_stock')->numeric()->default(0),
                        TextInput::make('maximum_stock')->numeric()->default(0),
                    ]),
                TextInput::make('qty')->stripCharacters(',')->numeric(),
                Select::make('unit_id')->options(Unit::all()->pluck('name', 'id'))->relationship('unit', 'name')->disabled(),
                TextInput::make('price')->mask(RawJs::make('$money($input)'))->stripCharacters(',')->numeric(),
                TextInput::make('notes')->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('detail')
            ->columns([
                Tables\Columns\TextColumn::make('item_id')->label('Item')
                    ->formatStateUsing(fn($state) => "[" . Item::find($state)->code . "] -- " . Item::find($state)->name),
                Tables\Columns\TextColumn::make('qty'),
                Tables\Columns\TextColumn::make('unit.name'),
                Tables\Columns\TextColumn::make('price')
                    ->state(fn(PurchaseOrderDetail $purchaseOrderDetail) => $purchaseOrderDetail->belongs_to->currency->symbol . '. ' . number_format($purchaseOrderDetail->price, 2)),
                Tables\Columns\TextColumn::make('notes'),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                return $query->orderBy('seqno');
            })
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->after(function (PurchaseOrderDetail $detail, Component $livewire) {
                        $detail->update(['seqno' => $this->getOwnerRecord()->details()->max('seqno') + 1]);
                        $subtotal = 0;
                        foreach ($detail->belongs_to->details as $purchaseOrderDetail)
                            $subtotal += ($purchaseOrderDetail->price * $purchaseOrderDetail->qty);

                        if ($detail->belongs_to->discount_is_percentage)
                            $discount = $subtotal * $detail->belongs_to->discount / 100;
                        else
                            $discount = $detail->belongs_to->discount;

                        $after_discount = $subtotal - $discount;
                        $tax = $after_discount * $detail->belongs_to->tax / 100;
                        $grandtotal = $after_discount + $tax;
                        $detail->belongs_to->update(['subtotal' => $subtotal, 'grandtotal' => $grandtotal]);
                        $livewire->dispatch('refreshPurchaseOrder');
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->iconButton()
                    ->after(function (PurchaseOrderDetail $detail, Component $livewire) {
                        $subtotal = 0;
                        foreach ($detail->belongs_to->details as $purchaseOrderDetail)
                            $subtotal += ($purchaseOrderDetail->price * $purchaseOrderDetail->qty);

                        if ($detail->belongs_to->discount_is_percentage)
                            $discount = $subtotal * $detail->belongs_to->discount / 100;
                        else
                            $discount = $detail->belongs_to->discount;

                        $after_discount = $subtotal - $discount;
                        $tax = $after_discount * $detail->belongs_to->tax / 100;
                        $grandtotal = $after_discount + $tax;
                        $detail->belongs_to->update(['subtotal' => $subtotal, 'grandtotal' => $grandtotal]);
                        $livewire->dispatch('refreshPurchaseOrder');
                    }),
                Tables\Actions\DeleteAction::make()->iconButton()
                    ->after(function (PurchaseOrderDetail $detail, Component $livewire) {
                        $subtotal = 0;
                        foreach ($detail->belongs_to->details as $purchaseOrderDetail)
                            $subtotal += ($purchaseOrderDetail->price * $purchaseOrderDetail->qty);

                        if ($detail->belongs_to->discount_is_percentage)
                            $discount = $subtotal * $detail->belongs_to->discount / 100;
                        else
                            $discount = $detail->belongs_to->discount;

                        $after_discount = $subtotal - $discount;
                        $tax = $after_discount * $detail->belongs_to->tax / 100;
                        $grandtotal = $after_discount + $tax;
                        $detail->belongs_to->update(['subtotal' => $subtotal, 'grandtotal' => $grandtotal]);
                        $livewire->dispatch('refreshPurchaseOrder');
                    }),
            ], ActionsPosition::BeforeColumns)
            ->bulkActions([
                BulkAction::make('delete')
                    ->requiresConfirmation()
                    ->action(fn(Collection $records) => $records->each->delete())
                    ->label('Delete Selected')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->after(function (PurchaseOrder $belongs_to, Component $livewire) {
                        $subtotal = 0;
                        if (@$belongs_to->details->count() == 0) {
                            $belongs_to->update(['subtotal' => 0, 'grandtotal' => 0]);
                            $livewire->dispatch('refreshPurchaseOrder');
                            return;
                        }
                        foreach ($belongs_to->details as $purchaseOrderDetail)
                            $subtotal += ($purchaseOrderDetail->price * $purchaseOrderDetail->qty);

                        if ($belongs_to->discount_is_percentage)
                            $discount = $subtotal * $belongs_to->discount / 100;
                        else
                            $discount = $belongs_to->discount;

                        $after_discount = $subtotal - $discount;
                        $tax = $after_discount * $belongs_to->tax / 100;
                        $grandtotal = $after_discount + $tax;
                        $belongs_to->update(['subtotal' => $subtotal, 'grandtotal' => $grandtotal]);
                        $livewire->dispatch('refreshPurchaseOrder');
                    }),
            ])
            ->paginated(false);
    }
}
