<?php

namespace App\Models;

use App\Traits\crudBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkOrder extends Model
{
    use HasFactory, SoftDeletes, crudBy;

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class, 'division_id');
    }

    public function work_order_status(): BelongsTo
    {
        return $this->belongsTo(WorkOrderStatus::class, 'work_order_status_id');
    }

    public function prev_work_order(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class, 'prev_work_order_id');
    }

    public function next_work_order(): HasOne
    {
        return $this->hasOne(WorkOrder::class, 'prev_work_order_id');
    }

    public function item_request(): HasOne
    {
        return $this->hasOne(ItemRequest::class, 'work_order_id');
    }
}
