<?php

namespace App\Filament\Exports;

use App\Models\Item;
use App\Models\WarehouseDetail;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Models\Export;

class ItemStockExporter extends Exporter
{
    protected static ?string $model = Item::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('code'),
            ExportColumn::make('name'),
            ExportColumn::make('item_specification.name')->label('Specification'),
            ExportColumn::make('item_category.name')->label('Category'),
            ExportColumn::make('item_type.name')->label('Type'),
            ExportColumn::make('item_brand.name')->label('Brand'),
            ExportColumn::make('unit.name'),
            ExportColumn::make('item_stock.qty')->label('Stock'),
            ExportColumn::make('minimum_stock'),
            ExportColumn::make('maximum_stock'),
            ExportColumn::make('warehouse_detail_ids')->label('Storage Locations')->formatStateUsing(function ($record) {
                $warehouse_detail_ids = json_decode($record->warehouse_detail_ids);
                if (empty($warehouse_detail_ids)) return '';
                $warehouse_details = "";
                foreach ($warehouse_detail_ids as $warehouse_detail_id) {
                    $warehouse_details .= WarehouseDetail::find($warehouse_detail_id)->code . ", ";
                }
                return substr($warehouse_details, 0, -2);
            }),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your item stock export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
