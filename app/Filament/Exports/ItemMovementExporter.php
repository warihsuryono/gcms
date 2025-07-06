<?php

namespace App\Filament\Exports;

use App\Models\ItemMovement;
use App\Models\WarehouseDetail;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Models\Export;

class ItemMovementExporter extends Exporter
{
    protected static ?string $model = ItemMovement::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('movement_at')->label('Movement At')->formatStateUsing(fn($state) => date('d F Y H:i:s', strtotime($state))),
            ExportColumn::make('in_out')->label('In/Out')->formatStateUsing(fn($state) => ucfirst($state)),
            ExportColumn::make('item_movement_type.name')->label('Type'),
            ExportColumn::make('item.name')->label('Item'),
            ExportColumn::make('qty'),
            ExportColumn::make('unit.name'),
            ExportColumn::make('item.item_specification.name')->label('Specification'),
            ExportColumn::make('item.item_category.name')->label('Category'),
            ExportColumn::make('item.item_type.name')->label('Type'),
            ExportColumn::make('item.item_brand.name')->label('Brand'),
            ExportColumn::make('item.warehouse_detail_ids')->label('Storage Locations')->formatStateUsing(function ($record) {
                $warehouse_detail_ids = json_decode($record->item->item_stock->warehouse_detail_ids);
                if (empty($warehouse_detail_ids)) return '';
                $warehouse_details = "";
                foreach ($warehouse_detail_ids as $warehouse_detail_id) {
                    $warehouse_details .= WarehouseDetail::find($warehouse_detail_id)->code . ", ";
                }
                return substr($warehouse_details, 0, -2);
            }),
            ExportColumn::make('notes'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your item movement export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
