<?php

namespace App\Models;

use App\Traits\crudBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FuelConsumption extends Model
{
    use HasFactory, SoftDeletes, crudBy;

    public function item_type(): BelongsTo
    {
        return $this->belongsTo(ItemType::class, 'item_type_id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function fuelpowered_equipment(): BelongsTo
    {
        return $this->belongsTo(FuelpoweredEquipment::class, 'fuelpowered_equipment_id');
    }
}
