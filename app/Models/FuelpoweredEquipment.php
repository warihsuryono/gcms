<?php

namespace App\Models;

use App\Traits\crudBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FuelpoweredEquipment extends Model
{
    use HasFactory, SoftDeletes, crudBy;

    public function item_type(): BelongsTo
    {
        return $this->belongsTo(ItemType::class, 'item_type_id');
    }
}
