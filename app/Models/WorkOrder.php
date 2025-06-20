<?php

namespace App\Models;

use App\Traits\crudBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function next_work_order(): HasMany
    {
        return $this->hasMany(WorkOrder::class, 'prev_work_order_id');
    }
}
