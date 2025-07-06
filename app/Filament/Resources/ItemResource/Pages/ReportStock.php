<?php

namespace App\Filament\Resources\ItemResource\Pages;

use App\Models\Item;
use Filament\Tables\Table;
use App\Models\WarehouseDetail;
use Filament\Resources\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use App\Filament\Resources\ItemResource;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Exports\ItemStockExporter;
use Filament\Tables\Concerns\InteractsWithTable;

class ReportStock extends Page implements HasTable
{
    use InteractsWithTable;
    protected static string $model = Item::class;
    protected static ?string $title = 'Item Stock Report';
    protected static string $resource = ItemResource::class;
    protected static string $view = 'filament.resources.item-resource.pages.report-stock';

    public static function table(Table $table): Table
    {
        return $table
            ->query(Item::query())
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
                TextColumn::make('warehouse_detail_ids')->label('Storage Locations')->formatStateUsing(function ($record) {
                    $warehouse_detail_ids = json_decode($record->warehouse_detail_ids);
                    $warehouse_details = "";
                    foreach ($warehouse_detail_ids as $warehouse_detail_id) {
                        $warehouse_details .= WarehouseDetail::find($warehouse_detail_id)->code . ", ";
                    }
                    return substr($warehouse_details, 0, -2);
                })->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('item_specification')->relationship('item_specification', 'name')->searchable()->preload()->label('Specification'),
                SelectFilter::make('item_category')->relationship('item_category', 'name')->searchable()->preload()->label('Category'),
                SelectFilter::make('item_type')->relationship('item_type', 'name')->searchable()->preload()->label('Type'),
                SelectFilter::make('item_brand')->relationship('item_brand', 'name')->searchable()->preload()->label('Brand'),
            ])
            ->filtersFormColumns(2)
            ->paginated(false)
            ->headerActions([
                ExportAction::make()
                    ->exporter(ItemStockExporter::class)
                    ->label('Export')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color("success")
                    ->filename('item-stock-' . time())
                    ->columnMapping(false),
            ]);
    }
}
