<?php

namespace App\Models;

use App\Traits\crudBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ItemRequestDetail extends Model
{
    use HasFactory, SoftDeletes, crudBy;

    public function belongs_to(): BelongsTo
    {
        return $this->belongsTo(ItemRequest::class);
    }

    public function item_movement_type(): BelongsTo
    {
        return $this->belongsTo(ItemMovementType::class, 'item_movement_type_id');
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
