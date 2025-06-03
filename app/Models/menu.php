<?php

namespace App\Models;

use App\Traits\crudBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class menu extends Model
{
    use HasFactory, SoftDeletes, crudBy;

    public function childMenu(): HasMany
    {
        return $this->hasMany(menu::class, 'parent_id');
    }

    public function parentMenu(): BelongsTo
    {
        return $this->belongsTo(menu::class, 'parent_id', 'id');
    }
}
