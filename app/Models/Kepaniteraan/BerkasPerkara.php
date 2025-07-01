<?php

namespace App\Models\Kepaniteraan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
