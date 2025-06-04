<?php

namespace App\Filament\Resources\DashboardResource\Pages;

use App\Filament\Resources\DashboardResource;
use App\Traits\FilamentEditFunctions;
use Filament\Resources\Pages\EditRecord;

class EditDashboard extends EditRecord
{
    protected $routename = 'dashboards';
    use FilamentEditFunctions;
    protected static string $resource = DashboardResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
