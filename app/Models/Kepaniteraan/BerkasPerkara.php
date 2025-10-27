<?php

namespace App\Models\Kepaniteraan;

use Database\Factories\Kepaniteraan\BerkasPerkaraFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class BerkasPerkara extends Model
{
    /** @use HasFactory<BerkasPerkaraFactory> */
    use HasFactory;

    protected $fillable = [
        'jurnal_perkara_id',
        'tanggal_masuk',
        'status',
        'lokasi',
    ];

    public function peminjaman()
    {
        return $this->hasMany(PeminjamanBerkasPerkara::class);
    }

    public function jurnalPerkara()
    {
        return $this->belongsTo(JurnalPerkara::class);
    }
}
