<?php

namespace App\Filament\Resources\WorkOrderResource\Pages;

use App\Filament\Resources\WorkOrderResource;
use App\Traits\FilamentEditFunctions;
use Filament\Resources\Pages\EditRecord;

class EditWorkOrder extends EditRecord
{
    protected $routename = 'work-orders';
    use FilamentEditFunctions;
    protected static string $resource = WorkOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function mutateFormDataBeforeFill(array $data): array
    {
        $data['field_ids'] = json_decode($this->record->field_ids);
        return $data;
    }
}
