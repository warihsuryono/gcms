<?php

namespace App\Filament\Resources\ItemRequestResource\Pages;

use App\Filament\Resources\ItemRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewItemRequest extends ViewRecord
{
    protected static string $resource = ItemRequestResource::class;
    protected static string $view = 'itemrequests.view';

    public function work_order()
    {
        if (@$this->record->work_order_id > 0)
            redirect()->route('filament.' . env('PANEL_PATH') . '.resources.work-orders.view', $this->record->work_order_id);
    }
}
