<?php

namespace App\Models\Kepaniteraan;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Kepaniteraan\DataSaksi;

class JurnalPerkara extends Model
{
    protected $table = 'jurnal_perkaras';

    protected $fillable = [
        'nomor_perkara',
        'klasifikasi_perkara',
        'penggugat',
        'tergugat',
        'proses_terakhir',
    ];

    public function scopeSearch($query, $search)
    {
        return $query->where('nomor_perkara', 'like', "%{$search}%")
            ->orWhere('klasifikasi_perkara', 'like', "%{$search}%")
            ->orWhere('penggugat', 'like', "%{$search}%")
            ->orWhere('tergugat', 'like', "%{$search}%")
            ->orWhere('proses_terakhir', 'like', "%{$search}%");
    }

    // public function getNomorAttribute()
    // {
    //     $parts = explode('/', $this->nomor_perkara);
    //     return $parts[0] ?? null;
    // }

    // public function getTahunAttribute()
    // {
    //     $parts = explode('/', $this->nomor_perkara);
    //     return $parts[2] ?? null;
    // }

    public function scopeLatestPerkara($query)
    {
        return $query
        // order by tahun 265/Pdt.G/2025/PA.Ntn tahun 2025 dan nomor perkara paling besar yaitu 265
            ->orderByRaw("CAST(SUBSTRING_INDEX(nomor_perkara, '/', 1) AS UNSIGNED) DESC")
            ->orderByRaw("CAST(SUBSTRING_INDEX(nomor_perkara, '/', -1) AS UNSIGNED) DESC");
    }

    public function dataSaksi()
    {
        return $this->hasMany(DataSaksi::class, 'jurnal_perkara_id', 'id');
    }

    public function arsipPerkara()
    {
        return $this->hasMany(BerkasPerkara::class, 'jurnal_perkara_id', 'id');
    }
}
