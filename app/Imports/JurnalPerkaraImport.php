<?php

namespace App\Imports;

use App\Models\Kepaniteraan\JurnalPerkara;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Log;

class JurnalPerkaraImport implements ToCollection
{
    protected ?array $lastRecord = null;
    protected ?string $lastSection = null; // 'penggugat' | 'tergugat' | null

    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            $rowData = (array) $row->toArray();

            // Ambil kolom (0-based) sesuai CSV
            $nomorPerkara  = trim($rowData[1] ?? '');
            $klasifikasi   = trim($rowData[3] ?? '');
            $paraPihakCell = trim((string)($rowData[4] ?? ''));
            $tahapan       = trim($rowData[5] ?? '');

            // Jika ini baris utama (ada nomor_perkara) -> flush record sebelumnya, mulai yang baru
            if ($nomorPerkara !== '') {
                // simpan yang sebelumnya bila ada
                if ($this->lastRecord) {
                    JurnalPerkara::updateOrCreate(
                        ['nomor_perkara' => $this->lastRecord['nomor_perkara']],
                        $this->lastRecord
                    );
                }

                // inisialisasi record baru
                $this->lastRecord = [
                    'nomor_perkara'       => $nomorPerkara,
                    'klasifikasi_perkara' => $klasifikasi,
                    'penggugat'           => '',
                    'tergugat'            => '',
                    'proses_terakhir'     => $tahapan, // atau pakai $rowData[6] kalau mau "Status Perkara"
                ];

                // reset state seksi
                $this->lastSection = null;

                // baris utama biasanya isi paraPihakCell= "Penggugat :"
                if ($paraPihakCell !== '') {
                    $this->updateSectionOrAppendName($paraPihakCell);
                }

                // lanjut ke baris berikutnya
                continue;
            }

            // ------ Di bawah ini: baris lanjutan (nomor_perkara kosong) ------
            if (!$this->lastRecord) {
                Log::warning("Baris dilewati: tidak ada nomor_perkara & belum ada record aktif.");
                continue;
            }

            // Kolom para pihak bisa berisi:
            // - "Penggugat :" => set section
            // - "Tergugat:"   => set section
            // - Nama          => append ke section saat ini
            if ($paraPihakCell !== '') {
                $this->updateSectionOrAppendName($paraPihakCell);
            }

            // Kalau ada tahapan di baris lanjutan yang ingin kamu update (opsional):
            if ($tahapan !== '') {
                $this->lastRecord['proses_terakhir'] = $tahapan;
            }
        }

        // simpan record terakhir
        if ($this->lastRecord) {
            JurnalPerkara::updateOrCreate(
                ['nomor_perkara' => $this->lastRecord['nomor_perkara']],
                $this->lastRecord
            );
        }
    }

    /**
     * Menentukan section (penggugat/tergugat) atau menambahkan nama ke section aktif.
     */
    private function updateSectionOrAppendName(string $cell): void
    {
        $label = preg_replace('/\s+/',' ', trim($cell));

        // Deteksi heading
        if (preg_match('/^Penggugat\s*:$/i', $label)) {
            $this->lastSection = 'penggugat';
            return;
        }
        if (preg_match('/^Tergugat\s*:$/i', $label)) {
            $this->lastSection = 'tergugat';
            return;
        }

        // Jika bukan heading dan ada section aktif, anggap ini baris nama
        if ($this->lastSection && $this->lastRecord) {
            $names = $this->cleanInputMultiple($label);
            if (!empty($names)) {
                $joined = implode("\n", $names);
                if ($this->lastRecord[$this->lastSection] ?? '') {
                    $this->lastRecord[$this->lastSection] .= "\n" . $joined;
                } else {
                    $this->lastRecord[$this->lastSection] = $joined;
                }
            }
        }
    }

    /**
     * Bersihkan input dari karakter/prefix angka "2. Nama", dsb.
     * Di file ini nama biasanya satu per baris, tapi tetap jaga-jaga split ; atau ,.
     */
    private function cleanInputMultiple($value): array
    {
        $value = (string) ($value ?? '');
        $value = trim($value);
        if ($value === '') return [];

        // Pecah pakai ; atau , jika ada. Kalau tidak, tetap satu elemen.
        $parts = preg_split('/[;,]+/', $value) ?: [$value];

        $out = [];
        foreach ($parts as $p) {
            $p = trim($p);
            if ($p === '') continue;
            // Hapus prefix angka+ titik, mis: "2. RAHAMADI BIN IBRAHIM"
            $p = preg_replace('/^\d+\.\s*/', '', $p);
            $out[] = $p;
        }
        return $out;
    }
}