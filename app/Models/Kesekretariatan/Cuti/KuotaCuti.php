<?php

namespace App\Models\Kesekretariatan\Cuti;

use Illuminate\Database\Eloquent\Model;

class KuotaCuti extends Model {
    protected $table = 'leave_quotas';

    protected $fillable = [
        'user_id',
        'leave_type_id',
        'year',
        'total_allowed',
        'used_days'
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