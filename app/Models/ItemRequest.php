<?php

namespace App\Models;

use App\Traits\crudBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ItemRequest extends Model
{
    use HasFactory, SoftDeletes, crudBy;

    public function details(): HasMany
    {
        return $this->hasMany(ItemRequestDetail::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function work_order(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class, 'work_order_id');
    }

    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}
