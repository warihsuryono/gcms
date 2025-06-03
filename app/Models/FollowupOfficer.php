<?php

namespace App\Models;

use App\Traits\crudBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FollowupOfficer extends Model
{
    use HasFactory, SoftDeletes, crudBy;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
