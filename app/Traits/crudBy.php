<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait crudBy
{
    public static function bootcrudBy()
    {
        static::creating(function ($model) {
            if (!$model->isDirty('created_by'))
                $model->created_by = @Auth::user()->id;
            if (!$model->isDirty('updated_by'))
                $model->updated_by = @Auth::user()->id;
        });

        static::updating(function ($model) {
            if (!$model->isDirty('updated_by'))
                $model->updated_by = @Auth::user()->id;
        });

        static::deleting(function ($model) {
            if (!$model->isDirty('deleted_by'))
                $model->update(["deleted_by" => @Auth::user()->id]);
        });
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
