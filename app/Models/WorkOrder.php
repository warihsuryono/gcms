<?php

namespace App\Models;

use App\Traits\crudBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkOrder extends Model
{
    use HasFactory, SoftDeletes, crudBy;

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class, 'division_id');
    }
}
