<?php

namespace App\Filament\Resources\ItemResource\Pages;

use App\Filament\Resources\ItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewItem extends ViewRecord
{
    protected static string $resource = ItemResource::class;

    public function mutateFormDataBeforeFill(array $data): array
    {
        $data['warehouse_detail_ids'] = json_decode($this->record->warehouse_detail_ids);
        return $data;
    }
}
