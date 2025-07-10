<?php

namespace App\Models\Kepaniteraan;

use Illuminate\Database\Eloquent\Model;

class DataSaksi extends Model
{
    protected $table = 'data_saksis';
    
    protected $fillable = [
        'jurnal_perkara_id',
        'dari_pihak',
        'nik',
        'nama_lengkap',
        'bin_binti',
        'saksi_ke',
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

    public function jurnalPerkara()
    {
        return $this->belongsTo(JurnalPerkara::class, 'jurnal_perkara_id');
    }

    // get data saksi yang nomor perkaranya sama
    public static function getDataGroupedByCase()
    {
        return self::with('jurnalPerkara')
            ->select('jurnal_perkara_id', 'nama_lengkap', 'jenis_kelamin', 'alamat', 'created_at')
            ->get()
            ->groupBy(function ($item) {
                return $item->jurnalPerkara->nomor_perkara ?? 'Tidak Diketahui';
            }); 
        }
}
