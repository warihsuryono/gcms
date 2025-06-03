<?php

namespace App\Models;

use App\Traits\crudBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Leave extends Model
{
    use HasFactory, SoftDeletes, crudBy;

    public function User(){
        return $this->belongsTo(User::class);
    }
    public function LeaveType(){
        return $this->belongsTo(LeaveType::class);
    }

    public function CreatedBy(){
        return $this->belongsTo(User::class, 'created_by');
    }
    public function UpdatedBy(){
        return $this->belongsTo(User::class, 'updated_by');
    }
    
    public function ApprovedBy(){
        return $this->belongsTo(User::class, 'approved_by');
    }
    

    public function AcknowledgeBy(){
        return $this->belongsTo(User::class, 'acknowledge_by');
    }
}
