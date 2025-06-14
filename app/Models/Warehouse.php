<?php

namespace App\Models;

use App\Traits\crudBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Warehouse extends Model
{
    use HasFactory, SoftDeletes, crudBy;

    public function details(): HasMany
    {
        return $this->hasMany(WarehouseDetail::class);
    }

    public function pic_(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pic');
    }
}
