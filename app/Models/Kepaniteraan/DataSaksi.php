<?php

namespace App\Models\Kepaniteraan;

use Illuminate\Database\Eloquent\Model;

class DataSaksi extends Model
{
    protected $table = 'data_saksis';
    
    protected $fillable = [
        'nomor_perkara',
        'dari_pihak',
        'nik',
        'nama_lengkap',
        'bin_binti',
        'alamat',
        'tempat_tanggal_lahir',
        'email',
        'no_hp',
        'rt',
        'rw',
        'kelurahan',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'jenis_kelamin',
        'pekerjaan',
        'pendidikan',
        'agama',
        'status_kawin',
        'hubungan_dengan_penggugat_tergugat',
        'pernah_lihat_bertengkar',
        'status_pisah_rumah',
    ];

    public function getRouteKeyName()
    {
        return 'id';
    }
}
