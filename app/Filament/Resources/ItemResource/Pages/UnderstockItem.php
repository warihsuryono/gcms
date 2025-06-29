<?php

namespace App\Filament\Resources\ItemResource\Pages;

use App\Models\Item;
use Filament\Tables\Table;
use App\Models\WarehouseDetail;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use App\Filament\Resources\ItemResource;
use Filament\Tables\Concerns\InteractsWithTable;

class UnderstockItem extends Page implements HasTable
{
    use InteractsWithTable;
    protected static string $resource = ItemResource::class;
    protected static string $model = Item::class;

    protected static string $view = 'filament.resources.item-resource.pages.understock-item';

    public static function table(Table $table): Table
    {
        return $table
            ->query(Item::understock_items())
            ->columns([
                TextColumn::make('code'),
                TextColumn::make('name'),
                TextColumn::make('item_specification.name')->label('Specification'),
                TextColumn::make('item_category.name')->label('Category'),
                TextColumn::make('item_type.name')->label('Type'),
                TextColumn::make('item_brand.name')->label('Brand'),
                TextColumn::make('unit.name'),
                TextColumn::make('item_stock.qty')->label('Stock'),
                TextColumn::make('minimum_stock')->numeric(),
                TextColumn::make('maximum_stock')->numeric(),
                TextColumn::make('lifetime')->numeric(),
                TextColumn::make('warehouse_detail_ids')->label('Storage Locations')->formatStateUsing(function ($state, $record) {
                    $warehouse_detail_ids = json_decode($record->warehouse_detail_ids);
                    $warehouse_details = "";
                    foreach ($warehouse_detail_ids as $warehouse_detail_id) {
                        $warehouse_details .= WarehouseDetail::find($warehouse_detail_id)->code . ", ";
                    }
                    return substr($warehouse_details, 0, -2);
                })->toggleable(isToggledHiddenByDefault: true),
            ])
            ->paginated(false)
            ->actions([]);
    }
}
