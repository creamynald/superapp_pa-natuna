<?php

namespace App\Models\Kesekretariatan\Cuti;

use Illuminate\Database\Eloquent\Model;

class PermohonanCuti extends Model {

    protected $table = 'leave_requests';

    protected $fillable = [
        'user_id',
        'leave_type_id',
        'start_date',
        'end_date',
        'duration_days',
        'reason',
        'address_on_leave',
        'phone_on_leave',
        'status',
        'manager_id',
        'final_approver_id',
        'manager_note',
        'final_note',
        'doc_number',
        'employee_snapshot'
    ];

    protected $casts = [
        'start_date'=>'date',
        'end_date'=>'date',
        'employee_snapshot'=>'array'
    ];

    public function user()
    { 
        return $this->belongsTo(User::class); 
    }

    public function leaveType()
    { 
        return $this->belongsTo(TipeCuti::class); 
    }

    public function attachments()
    { 
        return $this->hasMany(LeaveAttachment::class); 
    }
}