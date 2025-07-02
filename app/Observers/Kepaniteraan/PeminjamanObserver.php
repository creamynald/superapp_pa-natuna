<?php

namespace App\Observers\Kepaniteraan;

use App\Models\Kepaniteraan\BerkasPerkara;
use App\Models\Kepaniteraan\PeminjamanBerkasPerkara;

class PeminjamanObserver
{
    /**
     * Handle the BerkasPerkara "created" event.
     */
    public function created(PeminjamanBerkasPerkara $peminjaman)
    {
        // Ambil berkas terkait dari relasi
        $berkas = $peminjaman->berkas;

        if ($berkas) {
            $berkas->update(['status' => 'dipinjam']);
        }
    }

    /**
     * Handle the BerkasPerkara "updated" event.
     */
    public function updated(PeminjamanBerkasPerkara $peminjaman)
    {
        $berkas = $peminjaman->berkas;

        if (!$peminjaman->tanggal_kembali && $berkas) {
            $berkas->update(['status' => 'dipinjam']);
        } elseif ($peminjaman->tanggal_kembali && $berkas) {
            $berkas->update(['status' => 'tersedia']);
        }
    }

    /**
     * Handle the BerkasPerkara "deleted" event.
     */
    public function deleted(BerkasPerkara $berkasPerkara): void
    {
        //
    }

    /**
     * Handle the BerkasPerkara "restored" event.
     */
    public function restored(BerkasPerkara $berkasPerkara): void
    {
        //
    }

    /**
     * Handle the BerkasPerkara "force deleted" event.
     */
    public function forceDeleted(BerkasPerkara $berkasPerkara): void
    {
        //
    }
}
