<?php

namespace App\Models\Kepaniteraan;

use Illuminate\Database\Eloquent\Model;
use App\Models\Kepaniteraan\BerkasPerkara;
use App\Models\User;

class PeminjamanBerkasPerkara extends Model
{
    protected $fillable = [
        'berkas_perkara_id',
        'user_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'keperluan'
    ];

    public function berkas()
    {
        return $this->belongsTo(BerkasPerkara::class, 'berkas_perkara_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
