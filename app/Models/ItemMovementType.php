<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\crudBy;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ItemMovementType extends Model
{
    use HasFactory, SoftDeletes, crudBy;
}
