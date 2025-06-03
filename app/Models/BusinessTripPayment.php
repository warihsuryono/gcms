<?php

namespace App\Models;

use App\Traits\crudBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BusinessTripPayment extends Model
{
    use HasFactory, SoftDeletes, crudBy;

    public function belongs_to(): BelongsTo
    {
        return $this->belongsTo(BusinessTrip::class);
    }
}
