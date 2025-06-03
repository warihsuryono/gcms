<?php

namespace App\Http\Controllers;

use App\Models\menu;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use Romans\Filter\IntToRoman;
use App\Models\PurchaseRequest;
use App\Models\PurchaseOrderDetail;
use App\Models\PurchaseRequestDetail;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Redirect;

class PurchaseOrderController extends Controller
{
    protected $IntToRoman;
    public function __construct()
    {
        $this->IntToRoman = new IntToRoman();
    }

    public function createPurchaseOrder($purchaseRequestDetailIds)
    {

        $purchaseRequestDetailIds = explode(',', $purchaseRequestDetailIds);
        $purchaseRequestDetails = PurchaseRequestDetail::whereIn('id', $purchaseRequestDetailIds)->get();
        $purchaseRequest = PurchaseRequest::find($purchaseRequestDetails[0]->purchase_request_id);

        if (!PrivilegeController::privilege_check(menu::where('url', 'purchase-orders')->get()->pluck('id'), 1)) {
            Notification::make()->title('Sorry, you don`t have the privilege!')->warning()->send();
            return redirect(env('PANEL_PATH') . '/purchase-requests/' . $purchaseRequest->id);
        }

        $doc_no = "PO/" . $this->IntToRoman->filter(date("m")) . "/" . date("Y") . "/";
        $last_doc = PurchaseOrder::whereLike('doc_no', $doc_no . "%")->orderBy('doc_no', 'desc')->first();
        if (!$last_doc) $doc_no = $doc_no . "001";
        else $doc_no = $doc_no . str_pad((str_replace($doc_no, "", $last_doc->doc_no) * 1) + 1, 3, '0', STR_PAD_LEFT);


        $purchaseOrder = PurchaseOrder::create([
            'doc_no' => $doc_no,
            'doc_at' => NOW(),
            'supplier_id' => $purchaseRequestDetails[0]->supplier_id,
            'purchase_request_id' => $purchaseRequest->id,
            'use_by' => $purchaseRequest->use_by,
            'use_at' => $purchaseRequest->use_at,
            'currency_id' => $purchaseRequest->currency_id,
            'tax' => $purchaseRequest->tax,
        ]);

        foreach ($purchaseRequestDetails as $key => $purchaseRequestDetail) {
            PurchaseOrderDetail::create([
                'purchase_order_id' => $purchaseOrder->id,
                'seqno' => ($key + 1),
                'item_id' => $purchaseRequestDetail->item_id,
                'qty' => $purchaseRequestDetail->qty,
                'unit_id' => $purchaseRequestDetail->unit_id,
                'price' => $purchaseRequestDetail->price,
                'notes' => $purchaseRequestDetail->notes,
            ]);
            PurchaseRequestDetail::find($purchaseRequestDetail->id)->update(['is_purchase_order' => 1, 'purchase_order_id' => $purchaseOrder->id]);
        }
        return redirect(env('PANEL_PATH') . '/purchase-orders/' . $purchaseOrder->id . '/edit');
    }
}
