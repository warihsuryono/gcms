<?php

namespace App\Models;

use App\Traits\crudBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BusinessTrip extends Model
{
    use HasFactory, SoftDeletes, crudBy;

    public function details(): HasMany
    {
        return $this->hasMany(BusinessTripDetail::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(BusinessTripPayment::class);
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function leader(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function team1(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function team2(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function team3(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function team4(): BelongsTo
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
