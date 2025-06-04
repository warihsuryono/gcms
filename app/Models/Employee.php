<?php

namespace App\Models;

use App\Traits\crudBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory, SoftDeletes, crudBy;

    public function subordinate(): HasMany
    {
        return $this->hasMany(user::class, 'leader_user_id');
    }

    public function leader(): BelongsTo
    {
        return $this->belongsTo(user::class, 'leader_user_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function employee_status(): BelongsTo
    {
        return $this->belongsTo(EmployeeStatus::class);
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function marriage_status(): BelongsTo
    {
        return $this->belongsTo(MarriageStatus::class);
    }

    public function degree(): BelongsTo
    {
        return $this->belongsTo(Degree::class);
    }
}
