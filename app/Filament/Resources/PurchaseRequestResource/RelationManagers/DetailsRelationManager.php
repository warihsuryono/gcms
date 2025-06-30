<?php

namespace App\Filament\Resources\PurchaseRequestResource\RelationManagers;

use Filament\Forms;
use App\Models\Item;
use App\Models\Unit;
use Filament\Tables;
use Livewire\Component;
use App\Models\Supplier;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Support\RawJs;
use Livewire\Attributes\On;
use App\Models\PurchaseRequestDetail;
use Filament\Forms\Components\Select;
use App\Tables\Columns\SelectCheckbox;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\ActionsPosition;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class DetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'details';
    public $checkdetails = [];

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('supplier_id')->options(Supplier::all()->pluck('name', 'id'))->relationship('supplier', 'name')->searchable()->preload()
                    ->createOptionForm(
                        [
                            Select::make('import_domestic')
                                ->options(['import' => 'Import', 'domestic' => 'Domestic', 'both' => 'Both'])->required(),
                            TextInput::make('name')->maxLength(100),
                            TextInput::make('pic')->maxLength(50),
                            TextInput::make('pic_phone')->tel()->maxLength(30),
                            TextInput::make('email')->email()->maxLength(100),
                            TextInput::make('address')->maxLength(255),
                            Select::make('city_id')->relationship('city', 'name')->searchable()->preload()->default(0),
                            Select::make('province_id')->relationship('province', 'name')->searchable()->preload()->default(0),
                            TextInput::make('country')->maxLength(100)->default(''),
                            TextInput::make('zipcode')->maxLength(10)->default(''),
                        ]
                    ),
                Select::make('item_id')->options(Item::all()->pluck('name', 'id'))->relationship('item', 'name')->searchable()->preload()
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
                TextInput::make('notes')->required()->maxLength(255),
                TextInput::make('qty')->stripCharacters(',')->numeric(),
                Select::make('unit_id')->options(Unit::all()->pluck('name', 'id'))->relationship('unit', 'name')->searchable()->preload()
                    ->createOptionForm([TextInput::make('name')->maxLength(50)]),
                TextInput::make('price')->mask(RawJs::make('$money($input)'))->stripCharacters(',')->numeric()->prefix('Rp. '),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('detail')
            ->columns([
                SelectCheckbox::make('')->visible($this->getOwnerRecord()->is_approved),
                Tables\Columns\TextColumn::make('is_purchase_order')->label('PO')
                    ->color(fn(PurchaseRequestDetail $record) => ($record->is_purchase_order) ? "success" : "danger")
                    ->state(fn(PurchaseRequestDetail $record) => ($record->is_purchase_order) ? $record->purchase_order->doc_no : "Not Yet")
                    ->url(fn(PurchaseRequestDetail $record) => ($record->is_purchase_order) ? '../purchase-orders/' . $record->purchase_order_id : ''),
                Tables\Columns\TextColumn::make('supplier.name'),
                Tables\Columns\TextColumn::make('item.name'),
                Tables\Columns\TextColumn::make('notes'),
                Tables\Columns\TextColumn::make('qty'),
                Tables\Columns\TextColumn::make('unit.name'),
                Tables\Columns\TextColumn::make('price')->money('Rp. ')
                    ->state(fn(PurchaseRequestDetail $purchaseRequestDetail) => $purchaseRequestDetail->belongs_to->currency->symbol . '. ' . number_format($purchaseRequestDetail->price, 2)),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                return $query->orderBy('seqno');
            })
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->after(function (PurchaseRequestDetail $detail, Component $livewire) {
                        $detail->update(['seqno' => $this->getOwnerRecord()->details()->max('seqno') + 1]);
                        $livewire->dispatch('refreshPurchaseRequest');
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->iconButton()->after(function (Component $livewire) {
                    $livewire->dispatch('refreshPurchaseRequest');
                }),
                Tables\Actions\DeleteAction::make()->iconButton()->after(function (Component $livewire) {
                    $livewire->dispatch('refreshPurchaseRequest');
                }),
            ], ActionsPosition::BeforeColumns)
            ->paginated(false);
    }

    #[On('createPurchaseOrder')]
    public function createPurchaseOrder(): void
    {
        $purchaseRequestDetailIds = '';
        foreach ($this->checkdetails as $ids => $value)
            $purchaseRequestDetailIds .= $ids . ',';
        $purchaseRequestDetailIds = substr($purchaseRequestDetailIds, 0, -1);
        redirect(env('PANEL_PATH') . '/purchase-orders/create/' . $purchaseRequestDetailIds);
    }
}
