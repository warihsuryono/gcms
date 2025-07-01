<?php

namespace App\Filament\Resources\ItemReceiptResource\Pages;

use App\Models\ItemReceipt;
use App\Models\PurchaseOrder;
use Romans\Filter\IntToRoman;
use Illuminate\Support\Facades\Auth;
use App\Traits\FilamentCreateFunctions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ItemReceiptResource;
use App\Models\ItemReceiptDetail;

class CreateItemReceipt extends CreateRecord
{
    use FilamentCreateFunctions;
    protected static string $resource = ItemReceiptResource::class;
    protected static bool $canCreateAnother = false;
    protected $IntToRoman;

    public function __construct()
    {
        $this->IntToRoman = new IntToRoman();
    }

    protected function getRedirectUrl(): string
    {
        return route('filament.' . env('PANEL_PATH') . '.resources.item-receipts.edit', $this->record->id);
    }

    protected function beforeFill(): void
    {
        $data['item_receipt_no'] = "";
        $item_receipt_no = "RECEIPT/" . $this->IntToRoman->filter(date("m")) . "/" . date("Y") . "/";
        $last_doc = ItemReceipt::whereLike('item_receipt_no', $item_receipt_no . "%")->orderBy('item_receipt_no', 'desc')->first();
        if (!$last_doc) $data['item_receipt_no'] = $item_receipt_no . "001";
        else $data['item_receipt_no'] = $item_receipt_no . str_pad((str_replace($item_receipt_no, "", $last_doc->item_receipt_no) * 1) + 1, 3, '0', STR_PAD_LEFT);

        if (@request()->get('purchase_order_id') > 0) {
            $purchase_order_id = request()->get('purchase_order_id');
            $purchase_order = PurchaseOrder::find(request()->get('purchase_order_id'));
            $item_receipt = ItemReceipt::create([
                'item_receipt_no' => $data['item_receipt_no'],
                'item_receipt_at' => now(),
                'purchase_order_id' => $purchase_order_id,
                'supplier_id' => $purchase_order->supplier_id,
                'shipment_company' => $purchase_order->shipment_company,
                'shipment_pic' => $purchase_order->shipment_pic,
                'shipment_phone' => $purchase_order->shipment_phone,
                'shipment_address' => $purchase_order->shipment_address,
                'shipment_at' => $purchase_order->delivery_at,
                'description' => $purchase_order->description,
            ]);
            foreach ($purchase_order->details as $purchase_order_detail) {
                $settled_qty = 0;
                $item_receipts = ItemReceipt::where('purchase_order_id', $purchase_order_id)->where('is_approved', 1)->get();
                if ($item_receipts->count() > 0) foreach ($item_receipts as $receipt) $settled_qty += @$receipt->details->where('item_id', $purchase_order_detail->item_id)->first()->qty;

                $qty = $purchase_order_detail->qty - $settled_qty;
                ItemReceiptDetail::create([
                    'item_receipt_id' => $item_receipt->id,
                    'seqno' => $purchase_order_detail->seqno,
                    'purchase_order_detail_id' => $purchase_order_detail->id,
                    'item_id' => $purchase_order_detail->item_id,
                    'unit_id' => $purchase_order_detail->unit_id,
                    'qty_po' => $purchase_order_detail->qty,
                    'qty' => $qty,
                    'qty_outstanding' => $qty,
                    'notes' => $purchase_order_detail->notes,
                ]);
            }
            redirect()->route('filament.' . env('PANEL_PATH') . '.resources.item-receipts.edit', $item_receipt->id);
        }
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['item_receipt_no'] = "";
        $item_receipt_no = "RECEIPT/" . $this->IntToRoman->filter(date("m")) . "/" . date("Y") . "/";
        $last_doc = ItemReceipt::whereLike('item_receipt_no', $item_receipt_no . "%")->orderBy('item_receipt_no', 'desc')->first();
        if (!$last_doc) $data['item_receipt_no'] = $item_receipt_no . "001";
        else $data['item_receipt_no'] = $item_receipt_no . str_pad((str_replace($item_receipt_no, "", $last_doc->item_receipt_no) * 1) + 1, 3, '0', STR_PAD_LEFT);

        return $data;
    }

    protected function afterCreate(): void
    {
        $purchase_order = PurchaseOrder::find($this->record->purchase_order_id);
        if (!$purchase_order) redirect()->route('filament.' . env('PANEL_PATH') . '.resources.item-receipts.edit', $this->record->id);
        foreach ($purchase_order->details as $purchase_order_detail) {
            $settled_qty = 0;
            $item_receipts = ItemReceipt::where('purchase_order_id', $this->record->purchase_order_id)->where('is_approved', 1)->get();
            if ($item_receipts->count() > 0) foreach ($item_receipts as $receipt) $settled_qty += @$receipt->details->where('item_id', $purchase_order_detail->item_id)->first()->qty;

            $qty = $purchase_order_detail->qty - $settled_qty;
            ItemReceiptDetail::create([
                'item_receipt_id' => $this->record->id,
                'seqno' => $purchase_order_detail->seqno,
                'purchase_order_detail_id' => $purchase_order_detail->id,
                'item_id' => $purchase_order_detail->item_id,
                'unit_id' => $purchase_order_detail->unit_id,
                'qty_po' => $purchase_order_detail->qty,
                'qty' => $qty,
                'qty_outstanding' => $qty,
                'notes' => $purchase_order_detail->notes,
            ]);
        }
        redirect()->route('filament.' . env('PANEL_PATH') . '.resources.item-receipts.edit', $this->record->id);
    }
}
