<?php

namespace App\Models\Kesekretariatan;
use App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'employees';
    protected $fillable = [
        'user_id',
        'nip',
        'pangkat_golongan',
        'tempat_lahir',
        'tanggal_lahir',
        'tmt_golongan',
        'jabatan',
        'tmt_pegawai',
        'pendidikan_terakhir',
        'tahun_pendidikan',
        'kgb_yad',
        'keterangan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
