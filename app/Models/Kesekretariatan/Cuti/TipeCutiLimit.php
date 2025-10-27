<?php

namespace App\Models\Kesekretariatan\Cuti;

use Illuminate\Database\Eloquent\Model;

class TipeCutiLimit extends Model {
    protected $table = 'leave_type_user_limits';
    
    protected $fillable = [
        'user_id',
        'leave_type_id',
        'quota_days'
    ];

    public function user()
    { 
        return $this->belongsTo(User::class); 
    }

    public function leaveType()
    { 
        return $this->belongsTo(LeaveType::class); 
    }
}