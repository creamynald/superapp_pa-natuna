<?php

namespace App\Imports;

use App\Models\Kepaniteraan\JurnalPerkara;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Log;

class JurnalPerkaraImport implements ToCollection
{
    protected ?array $lastRecord = null;

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            // Pastikan row array atau bisa diakses sebagai array
            if (!is_array($row->toArray())) {
                Log::warning("Baris dilewati karena format tidak sesuai.");
                continue;
            }

            $rowData = $row->toArray();

            // Asumsi urutan kolom CSV/Excel:
            // 0 => No,
            // 1 => Nomor Perkara,
            // 2 => Klasifikasi Perkara,
            // 3 => Penggugat,
            // 4 => Tergugat,
            // 5 => Proses Terakhir,

            $nomorPerkara = trim($rowData[1] ?? '');
            $klasifikasi = trim($rowData[2] ?? '');
            $penggugatRaw = $rowData[3] ?? '';
            $tergugatRaw = $rowData[4] ?? '';
            $prosesAkhir = trim($rowData[5] ?? '');

            // Jika tidak ada nomor_perkara, anggap ini lanjutan dari perkara sebelumnya
            if (empty($nomorPerkara)) {
                if ($this->lastRecord) {
                    // Ambil daftar penggugat dari data saat ini dan gabung ke yang lama
                    $newPenggugatList = $this->cleanInputMultiple($penggugatRaw);
                    $existingPenggugatList = $this->cleanInputMultiple($this->lastRecord['penggugat'] ?? '');

                    $mergedPenggugat = array_merge($existingPenggugatList, $newPenggugatList);

                    // Update lastRecord
                    $this->lastRecord['penggugat'] = implode("\n", $mergedPenggugat);

                    // Simpan perubahan ke database
                    JurnalPerkara::updateOrCreate(
                        ['nomor_perkara' => $this->lastRecord['nomor_perkara']],
                        $this->lastRecord
                    );

                } else {
                    Log::warning("Baris dilewati karena tidak ada nomor_perkara dan tidak ada record sebelumnya.");
                }

                continue;
            }

            // Bersihkan input dan pecah banyak nama
            $penggugatList = $this->cleanInputMultiple($penggugatRaw);
            $tergugatList = $this->cleanInputMultiple($tergugatRaw);

            $penggugat = implode("\n", $penggugatList);
            $tergugat = implode("\n", $tergugatList);

            // Jika tergugat kosong, isi dengan "(tidak ada)"
            if (empty($tergugat)) {
                $tergugat = '-';
            }

            // Simpan record baru
            $record = JurnalPerkara::updateOrCreate(
                ['nomor_perkara' => $nomorPerkara],
                [
                    'klasifikasi_perkara' => $klasifikasi,
                    'penggugat'           => $penggugat,
                    'tergugat'            => $tergugat,
                    'proses_terakhir'     => $prosesAkhir,
                ]
            );

            // Simpan sebagai lastRecord untuk digabung dengan baris berikutnya
            $this->lastRecord = $record->toArray();
        }
    }

    /**
     * Bersihkan input dari karakter ilegal seperti "2.RAHAMADI BIN IBRAHIM"
     */
    private function cleanInputMultiple($value): array
    {
        $value = (string) ($value ?? ''); // pastikan selalu string
        $value = trim($value);

        if (empty($value)) {
            return [];
        }

        // Pisahkan berdasarkan titik koma, titik, atau spasi
        $lines = preg_split('/[;,]+/', $value);
        $names = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if (!empty($line)) {
                // Hapus prefix angka + titik
                $name = preg_replace('/^\d+\./', '', $line);
                $names[] = trim($name);
            }
        }

        return $names;
    }
}