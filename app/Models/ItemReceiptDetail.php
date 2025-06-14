<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\crudBy;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemReceiptDetail extends Model
{
    use HasFactory, SoftDeletes, crudBy;

    public function belongs_to(): BelongsTo
    {
        return $this->belongsTo(ItemReceipt::class);
    }

    public function purchase_order_detail(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrderDetail::class, 'purchase_order_detail_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
