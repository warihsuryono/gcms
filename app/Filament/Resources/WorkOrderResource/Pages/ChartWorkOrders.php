<?php

namespace App\Filament\Resources\WorkOrderResource\Pages;

use App\Filament\Resources\WorkOrderResource;
use Filament\Resources\Pages\Page;

class ChartWorkOrders extends Page
{
    protected static string $resource = WorkOrderResource::class;
    protected ?string $heading = 'Work Orders Chart';
    protected static string $view = 'filament.resources.work-order-resource.pages.chart-work-orders';

    public function getBreadcrumbs(): array
    {
        return [
            'Work Orders',
            'Chart'
        ];
    }
}
