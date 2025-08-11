<?php

namespace App\Filament\Resources\Kesekretariatan\PegawaiResource\Pages;

use App\Filament\Resources\Kesekretariatan\PegawaiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Services\SimtepaSyncService;
use App\Models\Kesekretariatan\Pegawai;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;
use Filament\Forms;
use Spatie\Permission\Models\Role;

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
                ->color('warning')
                ->icon('heroicon-o-cloud-arrow-down')
                ->modalHeading('Ambil & perbarui data pegawai dari SIMTEPA')
                // full modal
                ->modalWidth('max-w-7xl') // sekitar 1280px
                // subheading
                ->modalSubheading('Sinkronisasi ini akan mengambil data pegawai dari SIMTEPA dan memperbarui data yang ada di sistem.')
                // Form akan diisi DINAMIS di mountUsing, berisi NIP yg kosong
                ->form([
                    Forms\Components\Hidden::make('__all_items'), // serialized items
                    Forms\Components\Repeater::make('missing_nips')
                        ->label('Lengkapi NIP yang belum tersedia')
                        ->schema([
                            Forms\Components\TextInput::make('nip')
                                ->label('NIP')->required()
                                ->rule('regex:/^\d{8,}$/') // sesuaikan pola NIP-mu
                                ->helperText('Masukkan angka NIP (min 8 digit)'),
                            Forms\Components\TextInput::make('nama')
                                ->label('Nama')->disabled()->dehydrated(true),
                             Forms\Components\TextInput::make('tanggal_lahir')
                                ->label('Tgl Lahir')->disabled()->dehydrated(true),
                        ])
                        ->columns(3)
                        ->addable(false)
                        ->deletable(false)
                        ->reorderable(false)
                        ->visible(fn ($get) => !empty($get('missing_nips'))),
                    Forms\Components\Placeholder::make('info')
                        ->content('Jika daftar di atas kosong, berarti semua data sudah punya NIP.'),
                ])
                // Ambil data & siapkan form-nya
                ->mountUsing(function (Actions\Action $action, Forms\ComponentContainer $form) {
                    $service = new SimtepaSyncService();
                    $items = $service->fetchAll(); // sudah robust & punya NK

                    if (empty($items)) {
                        $action->halt();
                        Notification::make()->title('Tidak ada data / gagal mengambil JSON.')->warning()->send();
                        return;
                    }

                    // cari yang tidak punya NIP, tapi punya NK (nama+tgl lahir)
                    $missing = [];
                    foreach ($items as $row) {
                        if (empty($row['nip']) && !empty($row['nama']) && !empty($row['tanggal_lahir'])) {
                            $missing[] = [
                                'nama'          => $row['nama'],
                                'tanggal_lahir' => $row['tanggal_lahir'],
                                'nip'           => '', // diisi manual
                            ];
                        }
                    }

                    // simpan semua items ke hidden (serialize ke json)
                    $form->fill([
                        '__all_items'  => json_encode($items),
                        'missing_nips' => $missing,
                    ]);
                })
                // Jalankan sinkron setelah NIP yg kosong dilengkapi
                ->action(function (array $data) {
                    // Ambil semua item
                    $items = json_decode((string) ($data['__all_items'] ?? '[]'), true) ?: [];

                    // Gabungkan input NIP manual: cocokkan per (nama + tgl lahir)
                    $manual = collect($data['missing_nips'] ?? [])
                        ->filter(fn ($r) => !empty($r['nip']) && !empty($r['nama']) && !empty($r['tanggal_lahir']))
                        ->keyBy(function ($r) {
                            return mb_strtolower(trim($r['nama'])) . '|' . $r['tanggal_lahir'];
                        });

                    foreach ($items as &$row) {
                        if (!empty($row['nip'])) {
                            continue;
                        }
                        if (!empty($row['nama']) && !empty($row['tanggal_lahir'])) {
                            $key = mb_strtolower(trim($row['nama'])) . '|' . $row['tanggal_lahir'];
                            if (isset($manual[$key])) {
                                $row['nip'] = $manual[$key]['nip']; // isi NIP hasil input
                            }
                        }
                    }
                    unset($row);

                    $created = 0;
                    $updated = 0;
                    $skipped = 0;

                    Role::findOrCreate('pegawai', 'web');

                    DB::transaction(function () use (&$items, &$created, &$updated, &$skipped) {
                        foreach ($items as $row) {
                            // Skip jika tetap tidak ada NIP (hindari error kolom NIP not null)
                            if (empty($row['nip'])) {
                                $skipped++;
                                continue;
                            }

                            // 1) Cari by NIP
                            $pegawai = Pegawai::where('nip', $row['nip'])->first();

                            // 2) (Opsional) fallback NK kalau mau—tp karena NIP sudah ada, biasanya tak diperlukan

                            if (!$pegawai) {
                                // Buat user
                                $pwd = !empty($row['tanggal_lahir'])
                                    ? str_replace('-', '', $row['tanggal_lahir'])
                                    : Str::random(10);

                                $user = User::create([
                                    'name'     => $row['nama'] ?? 'Pegawai',
                                    'email'    => null,
                                    'password' => bcrypt($pwd),
                                ]);

                                $user->assignRole('pegawai');

                                Pegawai::create([
                                    'user_id'             => $user->id,
                                    'nip'                 => $row['nip'],
                                    'nama'                => $row['nama'] ?? null,
                                    'tempat_lahir'        => $row['tempat_lahir'] ?? null,
                                    'tanggal_lahir'       => $row['tanggal_lahir'] ?? null,
                                    'pangkat_golongan'    => $row['pangkat_golongan'] ?? null,
                                    'jabatan'             => $row['jabatan'] ?? null,
                                    'tmt_golongan'        => $row['tmt_golongan'] ?? null,
                                    'tmt_pegawai'         => $row['tmt_pegawai'] ?? null,
                                    'pendidikan_terakhir' => $row['pendidikan_terakhir'] ?? null,
                                    'tahun_pendidikan'    => $row['tahun_pendidikan'] ?? null,
                                    'kgb_yad'             => $row['kgb_yad'] ?? null,
                                    'keterangan'          => $row['keterangan'] ?? null,
                                ]);

                                $created++;
                                continue;
                            }

                            // UPDATE
                            $pegawai->fill([
                                'nama'                => $row['nama'] ?? $pegawai->nama,
                                'tempat_lahir'        => $row['tempat_lahir'] ?? $pegawai->tempat_lahir,
                                'tanggal_lahir'       => $row['tanggal_lahir'] ?? $pegawai->tanggal_lahir,
                                'pangkat_golongan'    => $row['pangkat_golongan'] ?? $pegawai->pangkat_golongan,
                                'jabatan'             => $row['jabatan'] ?? $pegawai->jabatan,
                                'tmt_golongan'        => $row['tmt_golongan'] ?? $pegawai->tmt_golongan,
                                'tmt_pegawai'         => $row['tmt_pegawai'] ?? $pegawai->tmt_pegawai,
                                'pendidikan_terakhir' => $row['pendidikan_terakhir'] ?? $pegawai->pendidikan_terakhir,
                                'tahun_pendidikan'    => $row['tahun_pendidikan'] ?? $pegawai->tahun_pendidikan,
                                'kgb_yad'             => $row['kgb_yad'] ?? $pegawai->kgb_yad,
                                'keterangan'          => $row['keterangan'] ?? $pegawai->keterangan,
                            ])->save();

                            // Sinkron nama user
                            if ($pegawai->user && !empty($row['nama']) && $row['nama'] !== $pegawai->user->name) {
                                $pegawai->user->name = $row['nama'];
                                $pegawai->user->save();
                            }

                            $updated++;
                        }
                    });

                    Notification::make()
                        ->title("Sinkron selesai: {$created} dibuat, {$updated} diperbarui, {$skipped} dilewati (tanpa NIP).")
                        ->success()
                        ->send();
                })
                ->requiresConfirmation(), // tetap minta konfirmasi awal
        ];
    }
}