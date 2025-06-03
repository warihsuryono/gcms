<?php

namespace App\Filament\Resources\PaymentTypeResource\Pages;

use App\Filament\Resources\PaymentTypeResource;
use App\Traits\FilamentEditFunctions;
use Filament\Resources\Pages\EditRecord;

class EditPaymentType extends EditRecord
{
    protected $routename = 'payment-types';
    use FilamentEditFunctions;
    protected static string $resource = PaymentTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
