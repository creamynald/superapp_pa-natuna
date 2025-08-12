<?php

namespace App\Models\Kesekretariatan;
use App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $appends = ['progress_kelengkapan'];
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

    public function getProgressKelengkapanAttribute(): int
    {
        // Daftar kolom yang jadi indikator kelengkapan
        $fields = [
            'nip',
            'pangkat_golongan',
            'jabatan',
            'tempat_lahir',
            'tanggal_lahir',
            'tmt_golongan',
            'tmt_pegawai',
            'pendidikan_terakhir',
            'tahun_pendidikan',
            'kgb_yad',
            'keterangan',
        ];

        $total = count($fields) + 1; // +1 untuk user.name
        $filled = 0;

        foreach ($fields as $f) {
            $val = $this->{$f} ?? null;
            if (is_string($val)) {
                $val = trim($val);
            }
            if ($val !== null && $val !== '') {
                $filled++;
            }
        }

        // Cek relasi user.name
        if (optional($this->user)->name && trim($this->user->name) !== '') {
            $filled++;
        }

        $percent = (int) floor(($filled / max($total, 1)) * 100);
        return max(0, min(100, $percent));
    }
}
