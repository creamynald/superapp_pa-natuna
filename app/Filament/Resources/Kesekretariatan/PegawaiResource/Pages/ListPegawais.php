<?php

namespace App\Filament\Resources\Kesekretariatan\PegawaiResource\Pages;

use App\Filament\Resources\Kesekretariatan\PegawaiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Models\Kesekretariatan\Pegawai;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;
use App\Services\SimtepaSyncService;

class ListPegawais extends ListRecords
{
    protected static string $resource = PegawaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make('create')
                ->label('Tambah Pegawai')
                ->icon('heroicon-o-plus'),
            Actions\Action::make('sync-simtepa')
                ->label('Sinkron dari SIMTEPA')
                ->icon('heroicon-o-cloud-arrow-down')
                ->requiresConfirmation()
                ->modalHeading('Ambil & perbarui data pegawai dari SIMTEPA?')
                ->modalSubheading('Akan membuat/memperbarui data berdasarkan NIP.')
                ->action(function () {
                    $service = new SimtepaSyncService();
                    $items = $service->fetchAll();

                    if (empty($items)) {
                        Notification::make()->title('Tidak ada data / gagal mengambil JSON.')->warning()->send();
                        return;
                    }

                    $created = 0;
                    $updated = 0;

                    DB::transaction(function () use ($items, &$created, &$updated) {
                        foreach ($items as $row) {
                            // Upsert Pegawai by NIP
                            /** @var Pegawai $pegawai */
                            $pegawai = Pegawai::where('nip', $row['nip'])->first();

                            if (!$pegawai) {
                                // Buat user dulu (password = NIP)
                                $user = User::create([
                                    'name'     => $row['nama'] ?? 'Pegawai',
                                    'email'    => null, // isi nanti kalau ada
                                    'password' => bcrypt($row['nip']),
                                ]);

                                $pegawai = Pegawai::create([
                                    'user_id'             => $user->id,
                                    'nip'                 => $row['nip'],
                                    'nama'                => $row['nama'] ?? null,        // pastikan kolom 'nama' ada di tabel pegawais
                                    'pangkat_golongan'    => $row['pangkat_golongan'] ?? null,
                                    'tempat_lahir'        => $row['tempat_lahir'] ?? null,
                                    'tanggal_lahir'       => $row['tanggal_lahir'] ?? null,
                                    'tmt_golongan'        => $row['tmt_golongan'] ?? null,
                                    'jabatan'             => $row['jabatan'] ?? null,
                                    'tmt_pegawai'         => $row['tmt_pegawai'] ?? null,
                                    'pendidikan_terakhir' => $row['pendidikan_terakhir'] ?? null,
                                    'tahun_pendidikan'    => $row['tahun_pendidikan'] ?? null,
                                    'kgb_yad'             => $row['kgb_yad'] ?? null,
                                    'keterangan'          => $row['keterangan'] ?? null,
                                ]);

                                $created++;
                            } else {
                                // Update data pegawai
                                $pegawai->fill([
                                    'nama'                => $row['nama'] ?? $pegawai->nama,
                                    'pangkat_golongan'    => $row['pangkat_golongan'] ?? $pegawai->pangkat_golongan,
                                    'tempat_lahir'        => $row['tempat_lahir'] ?? $pegawai->tempat_lahir,
                                    'tanggal_lahir'       => $row['tanggal_lahir'] ?? $pegawai->tanggal_lahir,
                                    'tmt_golongan'        => $row['tmt_golongan'] ?? $pegawai->tmt_golongan,
                                    'jabatan'             => $row['jabatan'] ?? $pegawai->jabatan,
                                    'tmt_pegawai'         => $row['tmt_pegawai'] ?? $pegawai->tmt_pegawai,
                                    'pendidikan_terakhir' => $row['pendidikan_terakhir'] ?? $pegawai->pendidikan_terakhir,
                                    'tahun_pendidikan'    => $row['tahun_pendidikan'] ?? $pegawai->tahun_pendidikan,
                                    'kgb_yad'             => $row['kgb_yad'] ?? $pegawai->kgb_yad,
                                    'keterangan'          => $row['keterangan'] ?? $pegawai->keterangan,
                                ])->save();

                                // (opsional) sinkron juga nama user
                                if ($pegawai->user && !empty($row['nama']) && $row['nama'] !== $pegawai->user->name) {
                                    $pegawai->user->name = $row['nama'];
                                    $pegawai->user->save();
                                }

                                $updated++;
                            }
                        }
                    });

                    Notification::make()
                        ->title("Sinkron selesai: {$created} dibuat, {$updated} diperbarui")
                        ->success()
                        ->send();
                }),
        ];
    }
}
