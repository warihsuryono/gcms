<?php

namespace App\Filament\Resources\DashboardResource\Pages;

use App\Filament\Resources\DashboardResource;
use Filament\Facades\Filament;
use Filament\Resources\Pages\Page;

class ShowDashboard extends Page
{
    protected static string $resource = DashboardResource::class;

    protected static string $view = 'filament.resources.dashboard-resource.pages.show-dashboard';
    public function getTitle(): string
    {
        Filament::getCurrentPanel()->navigation(false);
        return '';
    }
}
