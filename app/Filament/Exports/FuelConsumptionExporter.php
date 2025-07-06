<?php

namespace App\Filament\Exports;

use App\Models\FuelConsumption;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class FuelConsumptionExporter extends Exporter
{
    protected static ?string $model = FuelConsumption::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('consumption_at'),
            ExportColumn::make('item_type.name'),
            ExportColumn::make('fuelpowered_equipment.name'),
            ExportColumn::make('quantity'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your fuel consumption export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
