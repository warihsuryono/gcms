<?php

namespace App\Filament\Resources\ItemRequestResource\RelationManagers;

use App\Models\FollowupOfficer;
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
use App\Models\ItemRequestDetail;
use Illuminate\Support\Facades\Auth;
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

    protected static ?string $title = 'Items';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('item_movement_type_id')->relationship('item_movement_type', 'name', fn($query) => $query->where('id', '<', '3'))->required()->default(1)->label('Request Type'),
                Select::make('item_id')
                    ->searchable()->preload()->required()->live()
                    ->options(function () {
                        return Item::all()->mapWithKeys(function ($item) {
                            return [$item->id => "[{$item->code}] -- {$item->name}"];
                        });
                    })
                    ->createOptionForm([
                        Select::make('item_specification_id')->relationship('item_specification', 'name')->searchable()->preload(),
                        Select::make('item_category_id')->relationship('item_category', 'name')->searchable()->preload(),
                        Select::make('item_type_id')->relationship('item_type', 'name')->searchable()->preload(),
                        Select::make('item_brand_id')->relationship('item_brand', 'name')->searchable()->preload(),
                        TextInput::make('name')->maxLength(255),
                        Select::make('unit_id')->relationship('unit', 'name')->searchable()->preload(),
                        TextInput::make('description')->maxLength(255),
                    ])
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        $set('unit_id', Item::find($get('item_id'))->unit_id);
                    }),
                TextInput::make('qty')->stripCharacters(',')->numeric()->required(),
                Select::make('unit_id')->options(Unit::all()->pluck('name', 'id'))->relationship('unit', 'name')->disabled(),
                TextInput::make('notes')->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        $is_stock_visible = false;
        if (Auth::user()->privilege->id == 1) $is_stock_visible = true;
        if (@FollowupOfficer::where(['user_id' => Auth::user()->id, 'action' => 'item-request-issue'])->first()->id > 0) $is_stock_visible = true;
        if (@FollowupOfficer::where(['user_id' => Auth::user()->id, 'action' => 'stock-keeper'])->first()->id > 0) $is_stock_visible = true;

        return $table
            ->recordTitleAttribute('item_id')
            ->columns([
                Tables\Columns\TextColumn::make('item_movement_type.name')->label('Request Type'),
                Tables\Columns\TextColumn::make('item_id')->label('Item')
                    ->formatStateUsing(fn($state) => "[" . Item::find($state)->code . "] -- " . Item::find($state)->name),
                Tables\Columns\TextColumn::make('qty')->alignRight(),
                Tables\Columns\TextColumn::make('stock')->default(fn($record) => ItemStock::find($record->item_id)->qty ?? 0)->alignRight()
                    ->color(fn($record) => (@ItemStock::find($record->item_id)->qty < $record->qty) ? 'danger' : 'primary')
                    ->visible($is_stock_visible)
                    ->toggleable(isToggledHiddenByDefault: true),
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
                })->visible($is_stock_visible)->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->after(function (ItemRequestDetail $detail, Component $livewire) {
                        $detail->update([
                            'seqno' => $this->getOwnerRecord()->details()->max('seqno') + 1,
                            'unit_id' => $detail->item->unit_id
                        ]);
                        $livewire->dispatch('refreshItemRequest');
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->iconButton()->after(function (Component $livewire) {
                    $livewire->dispatch('refreshItemRequest');
                }),
                Tables\Actions\DeleteAction::make()->iconButton()->after(function (Component $livewire) {
                    $livewire->dispatch('refreshItemRequest');
                }),
            ], ActionsPosition::BeforeColumns)
            ->paginated(false);
    }
}
