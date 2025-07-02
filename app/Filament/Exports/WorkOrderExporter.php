<?php

namespace App\Filament\Exports;

use App\Models\Field;
use App\Models\WorkOrder;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Models\Export;

class WorkOrderExporter extends Exporter
{
    protected static ?string $model = WorkOrder::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('work_order_status.name')->label('Status'),
            ExportColumn::make('work_start')->label('Start At'),
            ExportColumn::make('work_end')->label('End At'),
            ExportColumn::make('division.name')->label('Division'),
            ExportColumn::make('field_ids')->label('Fields')->formatStateUsing(function ($record) {
                $field_ids = json_decode($record->field_ids);
                $fields = "";
                foreach ($field_ids as $field_id) {
                    $fields .= Field::find($field_id)->name . ", ";
                }
                return $fields;
            }),
            ExportColumn::make('works')->formatStateUsing(function ($record) {
                return str_replace('<br>', chr(13), $record->works);
            })->label('Works'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your work order export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
