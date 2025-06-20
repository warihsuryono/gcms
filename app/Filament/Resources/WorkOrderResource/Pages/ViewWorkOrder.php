<?php

namespace App\Filament\Resources\WorkOrderResource\Pages;

use App\Filament\Resources\WorkOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewWorkOrder extends ViewRecord
{
    protected static string $resource = WorkOrderResource::class;
    protected static string $view = 'workorders.view';

    public function prev_work_order()
    {
        if (@$this->record->prev_work_order_id > 0)
            redirect()->route('filament.' . env('PANEL_PATH') . '.resources.work-orders.view', $this->record->prev_work_order_id);
    }

    public function next_work_order()
    {
        if (@$this->record->next_work_order->id > 0)
            redirect()->route('filament.' . env('PANEL_PATH') . '.resources.work-orders.view', $this->record->next_work_order->id);
    }

    public function item_request()
    {
        if (@$this->record->item_request->id > 0)
            redirect()->route('filament.' . env('PANEL_PATH') . '.resources.item-requests.view', $this->record->item_request->id);
    }
}
