<?php

namespace App\Models\Kepaniteraan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class BerkasPerkara extends Model
{
    /** @use HasFactory<\Database\Factories\Kepaniteraan\BerkasPerkaraFactory> */
    use HasFactory;

    protected $fillable = [
        'nomor_perkara',
        'penggugat',
        'tergugat',
        'tanggal_masuk',
        'status',
        'lokasi',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->created_by = Auth::id();
            $model->updated_by = Auth::id();
        });

        static::updating(function ($model) {
            $model->updated_by = Auth::id();
        });
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
