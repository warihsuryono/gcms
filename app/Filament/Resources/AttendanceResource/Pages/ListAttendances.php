<?php

namespace App\Filament\Resources\AttendanceResource\Pages;

use App\Filament\Exports\AttendanceExporter;
use App\Filament\Resources\AttendanceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAttendances extends ListRecords
{
    protected static string $resource = AttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->icon('heroicon-o-plus'),
            Actions\ExportAction::make()
                    ->exporter(AttendanceExporter::class)
                    ->label('Export')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color("success")
                    ->filename('attendances'.time())
                    ->columnMapping(false)
        ];
    }
}
