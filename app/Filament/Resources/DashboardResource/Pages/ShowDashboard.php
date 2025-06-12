<?php

namespace App\Filament\Resources\DashboardResource\Pages;

use App\Filament\Resources\DashboardResource;
use App\Models\Dashboard;
use App\Models\Division;
use App\Models\WorkOrder;
use Filament\Facades\Filament;
use Filament\Resources\Pages\Page;
use PhpParser\Node\Expr\AssignOp\Div;

class ShowDashboard extends Page
{
    protected static string $resource = DashboardResource::class;

    protected static string $view = 'filament.resources.dashboard-resource.pages.show-dashboard';
    public function getTitle(): string
    {
        Filament::getCurrentPanel()->navigation(false);
        return '';
    }

    public function getViewData(): array
    {
        $work_orders = WorkOrder::where('work_start', '>=', date("Y-m-d") . ' 00:00:00')->where('work_start', '<=', date("Y-m-d") . ' 23:59:59');
        $division_ids = $work_orders->get()->pluck('division_id')->unique();
        if (request()->get('division_id')) $work_orders = $work_orders->where('division_id', request()->get('division_id'));
        return [
            'dashboard' => Dashboard::find(1),
            'work_orders' => $work_orders->orderBy('division_id')->orderBy('work_start')->get(),
            'divisions' => Division::whereIn('id', $division_ids)->orderBy('id')->get(),
        ];
    }
}
