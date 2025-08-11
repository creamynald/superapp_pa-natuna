<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Arr;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Carbon;

class SimtepaSyncService
{
    public function fetchAll(): array
    {
        $base  = config('simtepa.base');
        $token = config('simtepa.token');
        $paths = config('simtepa.paths', []);

        $all = [];

        foreach ($paths as $path) {
            $url = "{$base}/{$path}/json/{$token}";

            try {
                $resp = Http::withOptions(['http_errors' => false])
                    ->timeout(20)
                    ->retry(2, 500)
                    ->acceptJson()
                    ->get($url);

                if ($resp->status() >= 500) {
                    \Log::warning("SIMTEPA {$path} HTTP {$resp->status()}", ['url' => $url, 'body' => $resp->body()]);
                    continue;
                }

                if ($resp->failed()) {
                    \Log::warning("SIMTEPA {$path} failed HTTP {$resp->status()}", ['url' => $url, 'body' => $resp->body()]);
                    continue;
                }

                $json = $resp->json();

                if (!is_array($json)) {
                    \Log::warning("SIMTEPA {$path} non-JSON response", ['url' => $url, 'snippet' => substr($resp->body(), 0, 200)]);
                    continue;
                }

                $items = Arr::isAssoc($json) ? [$json] : $json;

                foreach ($items as $item) {
                    $mapped = $this->mapItem($item, $path);

                    // syarat minimal: punya natural-key (nama + tgl lahir) atau punya NIP
                    if (!empty($mapped['nip']) || !empty($mapped['nk'])) {
                        $all[] = $mapped;
                    }
                }
            } catch (RequestException $e) {
                \Log::error("SIMTEPA {$path} request exception: {$e->getMessage()}", ['url' => $url]);
                continue;
            } catch (\Throwable $e) {
                \Log::error("SIMTEPA {$path} unexpected error: {$e->getMessage()}", ['url' => $url]);
                continue;
            }
        }

        return $all;
    }

    /**
     * SESUAIKAN KEY di sini jika JSON berbeda.
     * Banyak feed SIMTEPA gunakan: nama_gelar, tgl_lahir, pangkat_ruang, tmt_jabatan_terakhir.
     */
    protected function mapItem(array $it, string $source): array
    {
        $nama = $it['nama_gelar'] ?? $it['nama'] ?? $it['name'] ?? null;
        $tgl  = $it['tgl_lahir'] ?? $it['tanggal_lahir'] ?? null;

        $tanggal_lahir = $this->toDate($tgl);

        return [
            'source'              => $source,
            'nip'                 => $it['nip'] ?? $it['NIP'] ?? null,   // bisa kosong di feed
            'nama'                => $nama,
            'tanggal_lahir'       => $tanggal_lahir,
            'tempat_lahir'        => $it['tempat_lahir'] ?? null,
            'pangkat_golongan'    => $it['pangkat_golongan'] ?? $it['golongan'] ?? ($it['pangkat_ruang'] ?? null),
            'jabatan'             => $it['jabatan'] ?? null,
            'tmt_golongan'        => $this->toDate($it['tmt_golongan'] ?? $it['tmt_jabatan_terakhir'] ?? null),
            'tmt_pegawai'         => $this->toDate($it['tmt_pegawai'] ?? null),
            'pendidikan_terakhir' => $it['pendidikan_terakhir'] ?? $it['pendidikan'] ?? null,
            'tahun_pendidikan'    => $this->toYear($it['tahun_pendidikan'] ?? $it['tahun_lulus'] ?? null),
            'kgb_yad'             => $this->toDate($it['kgb_yad'] ?? null),
            'keterangan'          => $it['keterangan'] ?? null,
            'foto_pegawai'        => $it['FotoPegawai'] ?? null,
            'foto_formal'         => $it['FotoFormal'] ?? null,

            // Natural key: gabungan nama + tanggal lahir (lowercased) → md5
            'nk'                  => ($nama && $tanggal_lahir)
                ? md5(mb_strtolower(trim($nama)).'|'.$tanggal_lahir)
                : null,
        ];
    }

    protected function toDate($val): ?string
    {
        if (empty($val)) return null;
        try {
            return Carbon::parse($val)->toDateString();
        } catch (\Throwable $e) {
            return null;
        }
    }

    protected function toYear($val): ?int
    {
        if (empty($val)) return null;
        $year = (int) substr((string) $val, 0, 4);
        return $year > 1900 && $year < 2100 ? $year : null;
    }
}
