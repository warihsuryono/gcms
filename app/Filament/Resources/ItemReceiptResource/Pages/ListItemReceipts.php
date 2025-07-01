<?php

namespace App\Filament\Resources\ItemReceiptResource\Pages;

use App\Filament\Resources\ItemReceiptResource;
use App\Traits\FilamentListFunctions;
use Filament\Resources\Pages\ListRecords;

class ListItemReceipts extends ListRecords
{
    use FilamentListFunctions;
    protected static string $resource = ItemReceiptResource::class;
}
