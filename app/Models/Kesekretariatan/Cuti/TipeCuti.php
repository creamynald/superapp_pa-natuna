<?php

namespace App\Models\Kesekretariatan\Cuti;

use Illuminate\Database\Eloquent\Model;

class TipeCuti extends Model {
    protected $table = 'leave_types';

    protected $fillable = [
        'name',
        'default_quota_days',
        'require_attachment'
    ];

    public function userLimits()
    { 
        return $this->hasMany(LeaveTypeUserLimit::class); 
    }
}
