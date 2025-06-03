<?php

namespace App\Filament\Resources\PresenceScheduleResource\Pages;

use App\Filament\Exports\PresenceScheduleExporter;
use App\Filament\Resources\PresenceScheduleResource;
use App\Traits\FilamentListFunctions;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Resources\Pages\ListRecords;

class ListPresenceSchedules extends ListRecords
{
    protected $routename = 'presence-schedules';
    // use FilamentListFunctions;
    protected static string $resource = PresenceScheduleResource::class;

    protected function getHeaderActions():array{
        return [
            CreateAction::make()->icon('heroicon-o-plus'),
            ExportAction::make()
                ->exporter(PresenceScheduleExporter::class)
                ->label('Export')
                ->icon('heroicon-o-arrow-down-tray')
                ->color("success")
                ->filename('presence-schedules'.time())
                ->columnMapping(false)
        ];
    }
}
