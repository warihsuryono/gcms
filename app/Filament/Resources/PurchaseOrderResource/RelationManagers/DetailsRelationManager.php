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
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\BulkAction;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\ActionsPosition;
use Illuminate\Database\Eloquent\Collection;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Resources\PurchaseOrderResource\Pages\ViewPurchaseOrder;

class DetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'details';
    protected static ?string $title = 'Detail Items';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('item_id')->searchable()->preload()->live()->label('Item')
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
                TextInput::make('qty')->stripCharacters(',')->numeric()->suffix(fn(Get $get) => Item::find($get('item_id'))->unit->name ?? ''),
                // TextInput::make('price')->mask(RawJs::make('$money($input)'))->stripCharacters(',')->numeric(),
                TextInput::make('notes')->maxLength(255),
                Hidden::make('unit_id'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('item_id')->label('Item')
                    ->formatStateUsing(fn($state) => "[" . Item::find($state)->code . "] -- " . Item::find($state)->name),
                Tables\Columns\TextColumn::make('qty'),
                Tables\Columns\TextColumn::make('unit.name'),
                // Tables\Columns\TextColumn::make('price')
                //     ->state(fn(PurchaseOrderDetail $purchaseOrderDetail) => $purchaseOrderDetail->belongs_to->currency->symbol . '. ' . number_format($purchaseOrderDetail->price, 2)),
                Tables\Columns\TextColumn::make('notes'),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                return $query->orderBy('seqno');
            })
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Add Item')->icon('heroicon-o-plus')
                    ->after(function (PurchaseOrderDetail $detail, Component $livewire) {
                        $detail->update(['seqno' => $this->getOwnerRecord()->details()->max('seqno') + 1]);
                        $livewire->dispatch('refreshPurchaseOrder');
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->iconButton()
                    ->after(function (PurchaseOrderDetail $detail, Component $livewire) {
                        $livewire->dispatch('refreshPurchaseOrder');
                    }),
                Tables\Actions\DeleteAction::make()->iconButton()
                    ->after(function (PurchaseOrderDetail $detail, Component $livewire) {
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
                    ->visible(fn() => strpos($_SERVER['HTTP_REFERER'], '/edit'))
            ])
            ->paginated(false);
    }
}
