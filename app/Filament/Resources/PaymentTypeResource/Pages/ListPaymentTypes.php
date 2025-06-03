<?php

namespace App\Filament\Resources\PaymentTypeResource\Pages;

use App\Filament\Resources\PaymentTypeResource;
use App\Traits\FilamentListFunctions;
use Filament\Resources\Pages\ListRecords;

class ListPaymentTypes extends ListRecords
{
    use FilamentListFunctions;
    protected static string $resource = PaymentTypeResource::class;
}
