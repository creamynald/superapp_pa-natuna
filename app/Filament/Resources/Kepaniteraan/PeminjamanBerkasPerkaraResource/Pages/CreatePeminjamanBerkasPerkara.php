<?php

namespace App\Filament\Resources\Kepaniteraan\PeminjamanBerkasPerkaraResource\Pages;

use App\Filament\Resources\Kepaniteraan\PeminjamanBerkasPerkaraResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Kepaniteraan\BerkasPerkara;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;

class CreatePeminjamanBerkasPerkara extends CreateRecord
{
    protected static string $resource = PeminjamanBerkasPerkaraResource::class;

    protected function beforeCreate(): void
    {
        $data = $this->data;
        $berkasId = $data['berkas_perkara_id'] ?? null;

        if (!$berkasId) {
            Notification::make()
                ->title('Validasi Gagal')
                ->body('Silakan pilih berkas perkara.')
                ->danger()
                ->send();

            throw ValidationException::withMessages([
                'berkas_perkara_id' => ['Silakan pilih berkas perkara.'],
            ]);
        }

        $berkas = BerkasPerkara::find($berkasId);

        if (!$berkas) {
            Notification::make()
                ->title('Berkas Tidak Ditemukan')
                ->body('Berkas yang Anda pilih tidak ditemukan.')
                ->danger()
                ->send();

            throw ValidationException::withMessages([
                'berkas_perkara_id' => ['Berkas tidak ditemukan.'],
            ]);
        }

        if ($berkas->status === 'dipinjam') {
            $peminjamanAktif = $berkas->peminjaman()
                ->whereNull('tanggal_kembali')
                ->first();

            $peminjam = $peminjamanAktif?->user?->name ?? 'Tidak Diketahui';

            Notification::make()
                ->title('Berkas Sedang Dipinjam')
                ->body("Berkas ini sedang dipinjam oleh {$peminjam}.")
                ->danger()
                ->send();

            // 🔥 Hentikan proses create dengan ValidationException
            throw ValidationException::withMessages([
                'berkas_perkara_id' => ["Berkas ini sedang dipinjam oleh {$peminjam}"],
            ]);
        }

        if ($berkas->status === 'tersedia') {
            // Update status ke 'dipinjam'
            $berkas->update(['status' => 'dipinjam']);
        } else {
            Notification::make()
                ->title('Berkas Tidak Tersedia')
                ->body('Berkas yang Anda pilih tidak tersedia untuk dipinjam.')
                ->danger()
                ->send();

            throw ValidationException::withMessages([
                'berkas_perkara_id' => ['Berkas tidak tersedia untuk dipinjam.'],
            ]);
        }
    }
}
