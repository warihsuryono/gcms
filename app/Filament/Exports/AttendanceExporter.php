<?php

namespace App\Filament\Exports;

use App\Models\Attendance;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class AttendanceExporter extends Exporter
{
    protected static ?string $model = Attendance::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('user.name')->label('Nama Karyawan'),
            ExportColumn::make('tap_in')->label("Waktu Masuk"),
            ExportColumn::make('tap_out')->label("Waktu Pulang"),
            ExportColumn::make('lat_in')->label('Lokasi Masuk (Latitude)'),
            ExportColumn::make('lon_in')->label('Lokasi Masuk (Longitude)'),
            ExportColumn::make('lat_out')->label('Lokasi Pulang (Latitude)'),
            ExportColumn::make('lon_out')->label('Lokasi Pulang (Longitude)'),
            ExportColumn::make('day_minutes')->label("Total Menit"),
            ExportColumn::make('id')->label('Total Jam Kerja')->formatStateUsing(function ($record) {
                $hours = intdiv($record->day_minutes, 60);
                $minutes = $record->day_minutes % 60;
                if ($hours == 0) {
                    return "{$minutes} minutes";
                }
                return "{$hours} hours {$minutes} minutes";
            }),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your attendance export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
