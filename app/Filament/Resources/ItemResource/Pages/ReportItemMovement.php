<?php

namespace App\Filament\Resources\ItemResource\Pages;

use App\Filament\Exports\ItemMovementExporter;
use Filament\Tables\Table;
use App\Models\WarehouseDetail;
use Filament\Resources\Pages\Page;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use App\Filament\Resources\ItemResource;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Exports\ItemStockExporter;
use App\Models\ItemMovement;
use Filament\Tables\Concerns\InteractsWithTable;

class ReportItemMovement extends Page implements HasTable
{
    use InteractsWithTable;
    protected static string $model = ItemMovement::class;
    protected static ?string $title = 'Item Movement Report';
    protected static string $resource = ItemResource::class;
    protected static string $view = 'filament.resources.item-resource.pages.report-item-movement';

    public static function table(Table $table): Table
    {
        return $table
            ->query(ItemMovement::query())
            ->columns([
                TextColumn::make('movement_at')->dateTime('d F Y H:i:s'),
                TextColumn::make('in_out')->label('In/Out')->formatStateUsing(fn($state) => ucfirst($state)),
                TextColumn::make('item_movement_type.name')->label('Type'),
                TextColumn::make('item.name')->label('Item'),
                TextColumn::make('qty')->numeric(),
                TextColumn::make('unit.name'),
                TextColumn::make('item.item_specification.name')->label('Specification')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('item.item_category.name')->label('Category')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('item.item_type.name')->label('Type')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('item.item_brand.name')->label('Brand')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('item.warehouse_detail_ids')->label('Storage Locations')->formatStateUsing(function ($record) {
                    $warehouse_detail_ids = json_decode($record->item->item_stock->warehouse_detail_ids);
                    $warehouse_details = "";
                    foreach ($warehouse_detail_ids as $warehouse_detail_id) {
                        $warehouse_details .= WarehouseDetail::find($warehouse_detail_id)->code . ", ";
                    }
                    return substr($warehouse_details, 0, -2);
                })->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('notes')->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('movement_at')
                    ->form([DatePicker::make('movement_at_from')->default(date('Y-m-d', strtotime('first day of this month'))), DatePicker::make('movement_at_until')->default(date('Y-m-d', strtotime('last day of this month')))])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['movement_at_from'], fn(Builder $query, $date): Builder => $query->whereDate('movement_at', '>=', $date))
                            ->when($data['movement_at_until'], fn(Builder $query, $date): Builder => $query->whereDate('movement_at', '<=', $date));
                    })->columns(2),
                SelectFilter::make('in_out')->label('In/Out')->options(['in' => 'In', 'out' => 'Out']),
                SelectFilter::make('item_movement_type')->relationship('item_movement_type', 'name')->label('Type'),
                SelectFilter::make('item')->relationship('item', 'name')->label('Item')->searchable()->preload(),
            ])
            ->filtersFormColumns(2)
            ->paginated(false)
            ->headerActions([
                ExportAction::make()
                    ->exporter(ItemMovementExporter::class)
                    ->label('Export')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color("success")
                    ->filename('item-movemnets-' . time())
                    ->columnMapping(false),
            ]);
    }
}
