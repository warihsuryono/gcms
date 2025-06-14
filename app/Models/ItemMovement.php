<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\crudBy;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class ItemMovement extends Model
{
    use HasFactory, SoftDeletes, crudBy;

    public function item_movement_type(): BelongsTo
    {
        return $this->belongsTo(ItemMovementType::class, 'item_movement_type_id');
    }

    public function item_request_detail(): BelongsTo
    {
        return $this->belongsTo(ItemRequestDetail::class, 'item_request_detail_id');
    }

    public function item_receipt_detail(): BelongsTo
    {
        return $this->belongsTo(ItemReceiptDetail::class, 'item_receipt_detail_id');
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
