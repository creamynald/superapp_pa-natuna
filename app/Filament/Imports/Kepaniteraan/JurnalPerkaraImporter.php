<?php

namespace App\Filament\Imports\Kepaniteraan;

use App\Models\Kepaniteraan\JurnalPerkara;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\Log;


class JurnalPerkaraImporter extends Importer
{
    protected static ?string $model = JurnalPerkara::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nomor_perkara')
                ->requiredMapping(),
            ImportColumn::make('klasifikasi_perkara')
                ->requiredMapping(),
            ImportColumn::make('penggugat')
                ->requiredMapping(),
            ImportColumn::make('tergugat')
                ->requiredMapping(),
            ImportColumn::make('proses_terakhir')
                ->requiredMapping(),
        ];
    }

    public static function getMappings(): array
    {
        return [
            'nomor_perkara'         => 'Nomor Perkara',
            'klasifikasi_perkara'   => 'Klasifikasi Perkara',
            'penggugat'             => 'Penggugat',
            'tergugat'              => 'Tergugat',
            'proses_terakhir'       => 'Proses Terakhir',
        ];
    }

    public static function getDelimiter(): string
    {
        return ';'; // karena file CSV kamu pakai titik koma
    }

    public function resolveRecord(): ?JurnalPerkara
    {
        // Ambil dan bersihkan data
        $nomorPerkara = trim($this->data['nomor_perkara'] ?? '');
        $klasifikasi = trim($this->data['klasifikasi_perkara'] ?? '');
        $penggugat = $this->cleanInput($this->data['penggugat'] ?? '');
        $tergugat = $this->cleanInput($this->data['tergugat'] ?? '');
        $prosesAkhir = trim($this->data['proses_terakhir'] ?? '');

        // Skip jika kolom penting kosong
        if (empty($nomorPerkara) || empty($klasifikasi) || empty($penggugat) || empty($tergugat)) {
            Log::warning("Baris dilewati karena data tidak lengkap.", [
                'nomor_perkara' => $nomorPerkara,
                'klasifikasi_perkara' => $klasifikasi,
                'penggugat' => $penggugat,
                'tergugat' => $tergugat,
            ]);

            return null;
        }

        return JurnalPerkara::updateOrCreate(
            ['nomor_perkara' => $nomorPerkara],
            [
                'klasifikasi_perkara' => $klasifikasi,
                'penggugat'           => $penggugat,
                'tergugat'            => $tergugat,
                'proses_akhir'        => $prosesAkhir,
            ]
        );
    }

    /**
     * Bersihkan input dari karakter ilegal seperti "2.RAHAMADI BIN IBRAHIM"
     */
    private function cleanInput($value): string
    {
        $value = trim((string)$value);

        // Hapus prefix angka + titik, contoh: "2.RAHAMADI" → "RAHAMADI"
        $value = preg_replace('/^\d+\./', '', $value);

        return $value;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your jurnal perkara import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}