<?php

namespace App\Filament\Resources\PurchaseOrderResource\Pages;

use Filament\Actions;
use Romans\Filter\IntToRoman;
use App\Traits\FilamentCreateFunctions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\PurchaseOrderResource;
use App\Models\PurchaseOrder;

class CreatePurchaseOrder extends CreateRecord
{
    use FilamentCreateFunctions;
    protected static string $resource = PurchaseOrderResource::class;
    protected static bool $canCreateAnother = false;
    protected $IntToRoman;

    public function __construct()
    {
        $this->IntToRoman = new IntToRoman();
    }

    protected function getRedirectUrl(): string
    {
        return route('filament.room.resources.purchase-orders.edit', $this->record->id);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['doc_no'] = "";
        $doc_no = "PO/" . $this->IntToRoman->filter(date("m")) . "/" . date("Y") . "/";
        $last_doc = PurchaseOrder::whereLike('doc_no', $doc_no . "%")->orderBy('doc_no', 'desc')->first();
        if (!$last_doc) $data['doc_no'] = $doc_no . "001";
        else $data['doc_no'] = $doc_no . str_pad((str_replace($doc_no, "", $last_doc->doc_no) * 1) + 1, 3, '0', STR_PAD_LEFT);

        return $data;
    }
}
