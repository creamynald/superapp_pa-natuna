<?php

namespace App\Models\Kesekretariatan;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class BukuTamu extends Model
{
    protected $fillable = [
        'name',
        'user_id',
        'purpose',
        'phoneNumber',
        'address',
        'photo',
        'signature',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
