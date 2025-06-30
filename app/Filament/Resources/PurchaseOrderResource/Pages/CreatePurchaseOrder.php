<?php

namespace App\Filament\Resources\PurchaseOrderResource\Pages;

use App\Models\Item;
use Filament\Actions;
use App\Models\PurchaseOrder;
use Romans\Filter\IntToRoman;
use App\Models\PurchaseOrderDetail;
use Illuminate\Support\Facades\Auth;
use App\Traits\FilamentCreateFunctions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\PurchaseOrderResource;
use App\Models\ItemRequest;

class CreatePurchaseOrder extends CreateRecord
{
    use FilamentCreateFunctions;
    protected static string $resource = PurchaseOrderResource::class;
    protected static bool $canCreateAnother = false;
    protected $IntToRoman;
    protected $is_understockitem = false;
    protected $item_request_id = 0;

    public function __construct()
    {
        $this->IntToRoman = new IntToRoman();
        $this->is_understockitem = request()->get('understockitem') ? true : false;
        $this->item_request_id = request()->get('item_request_id') ? request()->get('item_request_id') : 0;
    }

    protected function getRedirectUrl(): string
    {
        return route('filament.' . env('PANEL_PATH') . '.resources.purchase-orders.edit', $this->record->id);
    }

    protected function beforeFill(): void
    {
        $data['doc_no'] = "";
        $doc_no = "PO/" . $this->IntToRoman->filter(date("m")) . "/" . date("Y") . "/";
        $last_doc = PurchaseOrder::whereLike('doc_no', $doc_no . "%")->orderBy('doc_no', 'desc')->first();
        if (!$last_doc) $data['doc_no'] = $doc_no . "001";
        else $data['doc_no'] = $doc_no . str_pad((str_replace($doc_no, "", $last_doc->doc_no) * 1) + 1, 3, '0', STR_PAD_LEFT);

        if ($this->is_understockitem || $this->item_request_id > 0) {
            $item_request_id = 0;
            $use_by = Auth::user()->id;
            if ($this->item_request_id > 0) {
                $use_by = ItemRequest::find($this->item_request_id)->created_by;
                $item_request_id = $this->item_request_id;
            }

            $purchase_order = PurchaseOrder::create([
                'doc_no' => $data['doc_no'],
                'doc_at' => now(),
                'item_request_id' => $item_request_id,
                'delivery_at' => now()->addDays(7),
                'payment_type_id' => 1,
                'use_by' => $use_by,
                'use_at' => now()->addDays(7),
                'shipment_pic' => '',
                'shipment_address' => '',
                'notes' => 'Purchase Order created from understock item list',
                'currency_id' => 1,
                'tax' => 10
            ]);
            if ($this->is_understockitem) {
                foreach (Item::understock_items()->get() as $seqno =>  $item) {
                    $qty = $item->maximum_stock > 0 ? $item->maximum_stock - $item->item_stock->qty : 0;
                    PurchaseOrderDetail::create([
                        'purchase_order_id' => $purchase_order->id,
                        'seqno' => $seqno,
                        'item_id' => $item->id,
                        'qty' => $qty,
                        'unit_id' => $item->unit_id,
                    ]);
                }
            }
            if ($this->item_request_id > 0) {
                $item_request = ItemRequest::find($this->item_request_id);
                $seqno = -1;
                foreach ($item_request->details as $detail) {
                    $item_stock = $detail->item->item_stock;
                    if ($item_stock->qty < $detail->qty) {
                        $seqno++;
                        $qty = $detail->qty - $item_stock->qty;
                        PurchaseOrderDetail::create([
                            'purchase_order_id' => $purchase_order->id,
                            'seqno' => $seqno,
                            'item_id' => $detail->item_id,
                            'qty' => $qty,
                            'unit_id' => $detail->unit_id,
                            'purchase_request_detail_id' => $detail->id,
                        ]);
                    }
                }
            }
            redirect()->route('filament.' . env('PANEL_PATH') . '.resources.purchase-orders.edit', $purchase_order->id);
        }
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
