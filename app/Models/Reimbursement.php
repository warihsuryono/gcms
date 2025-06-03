<?php

namespace App\Models;

use App\Traits\crudBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reimbursement extends Model
{
    use HasFactory, SoftDeletes, crudBy;

    public function details(): HasMany
    {
        return $this->hasMany(ReimbursementDetail::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(ReimbursementPayment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function acknowledgeBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'acknowledge_by');
    }
}
