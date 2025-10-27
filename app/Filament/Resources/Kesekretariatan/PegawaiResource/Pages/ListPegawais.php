<?php

namespace App\Filament\Resources\Kesekretariatan\PegawaiResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Schema;
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
use App\Filament\Resources\Kesekretariatan\PegawaiResource\Widgets\PegawaiWidget;

class ListPegawais extends ListRecords
{
    protected static string $resource = PegawaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make('create')
                ->label('Tambah Pegawai')
                ->icon('heroicon-o-plus'),

            Action::make('sync-simtepa')
                ->label('Sinkron dari SIMTEPA')
                ->color('warning')
                ->icon('heroicon-o-cloud-arrow-down')
                ->modalHeading('Ambil & perbarui data pegawai dari SIMTEPA')
                ->modalWidth('max-w-7xl')
                ->modalSubheading('Sinkronisasi ini akan mengambil data pegawai dari SIMTEPA dan memperbarui data yang ada di sistem.')
                ->schema([
                    Hidden::make('__all_items'),
                    Repeater::make('missing_nips')
                        ->label('Lengkapi NIP yang belum tersedia')
                        ->schema([
                            TextInput::make('nip')
                                ->label('NIP')->required()
                                ->rule('regex:/^\d{8,}$/')
                                ->helperText('Masukkan angka NIP (min 8 digit)'),
                            TextInput::make('nama')
                                ->label('Nama')->disabled()->dehydrated(true),
                            TextInput::make('tanggal_lahir')
                                ->label('Tgl Lahir')->disabled()->dehydrated(true),
                        ])
                        ->columns(3)
                        ->addable(false)
                        ->deletable(false)
                        ->reorderable(false)
                        ->visible(fn ($get) => !empty($get('missing_nips'))),
                    Placeholder::make('info')
                        ->content('Jika daftar di atas kosong, berarti semua data sudah punya NIP.'),
                ])
                ->mountUsing(function (Action $action, Schema $schema) {
                    $service = new SimtepaSyncService();
                    $items = $service->fetchAll();

                    if (empty($items)) {
                        $action->halt();
                        Notification::make()
                            ->title('Tidak ada data / gagal mengambil JSON.')
                            ->warning()
                            ->send();
                        return;
                    }

                    // Ambil data lokal: employees + users.name
                    $localPegawai = DB::table('employees as e')
                        ->join('users as u', 'e.user_id', '=', 'u.id')
                        ->whereNotNull('e.nip')
                        ->whereNotNull('u.name')
                        ->whereNotNull('e.tanggal_lahir')
                        ->select('e.nip', 'u.name as nama', 'e.tanggal_lahir')
                        ->get()
                        ->keyBy(function ($row) {
                            return mb_strtolower(trim($row->nama)) . '|' . $row->tanggal_lahir;
                        });

                    // Lengkapi NIP dari data lokal jika cocok berdasarkan natural key
                    foreach ($items as &$row) {
                        if (empty($row['nip']) && !empty($row['nama']) && !empty($row['tanggal_lahir'])) {
                            $nk = mb_strtolower(trim($row['nama'])) . '|' . $row['tanggal_lahir'];
                            if (isset($localPegawai[$nk])) {
                                $row['nip'] = $localPegawai[$nk]->nip;
                            }
                        }
                    }
                    unset($row);

                    // Siapkan daftar yang benar-benar butuh input manual
                    $missing = [];
                    foreach ($items as $row) {
                        if (empty($row['nip']) && !empty($row['nama']) && !empty($row['tanggal_lahir'])) {
                            $missing[] = [
                                'nama'          => $row['nama'],
                                'tanggal_lahir' => $row['tanggal_lahir'],
                                'nip'           => '',
                            ];
                        }
                    }

                    $schema->fill([
                        '__all_items'  => json_encode($items),
                        'missing_nips' => $missing,
                    ]);
                })
                ->action(function (array $data) {
                    $items = json_decode((string) ($data['__all_items'] ?? '[]'), true) ?: [];

                    $manual = collect($data['missing_nips'] ?? [])
                        ->filter(fn ($r) => !empty($r['nip']) && !empty($r['nama']) && !empty($r['tanggal_lahir']))
                        ->keyBy(fn ($r) => mb_strtolower(trim($r['nama'])) . '|' . $r['tanggal_lahir']);

                    foreach ($items as &$row) {
                        if (!empty($row['nip'])) continue;
                        if (!empty($row['nama']) && !empty($row['tanggal_lahir'])) {
                            $key = mb_strtolower(trim($row['nama'])) . '|' . $row['tanggal_lahir'];
                            if (isset($manual[$key])) {
                                $row['nip'] = $manual[$key]['nip'];
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
                            if (empty($row['nip'])) {
                                $skipped++;
                                continue;
                            }

                            $pegawai = Pegawai::where('nip', $row['nip'])->first();

                            if (!$pegawai) {
                                // Buat user baru
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

                            // Siapkan data baru untuk perbandingan
                            $newData = [
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
                            ];

                            // Ambil data saat ini dari model (termasuk kolom nullable)
                            $currentData = $pegawai->getAttributes();

                            // Hanya bandingkan kolom yang ada di $newData
                            $relevantCurrent = array_intersect_key($currentData, $newData);
                            $relevantNew = array_intersect_key($newData, $currentData);

                            // Deteksi perubahan dengan array_diff_assoc (termasuk null vs string)
                            if (array_diff_assoc($relevantNew, $relevantCurrent) || array_diff_assoc($relevantCurrent, $relevantNew)) {
                                $pegawai->fill($newData)->save();

                                // Sinkronisasi nama user jika berubah
                                if ($pegawai->user && !empty($row['nama']) && $row['nama'] !== $pegawai->user->name) {
                                    $pegawai->user->update(['name' => $row['nama']]);
                                }

                                $updated++;
                            }
                            // Jika tidak ada perubahan, tidak dilakukan apa-apa → tidak dihitung sebagai updated
                        }
                    });

                    Notification::make()
                        ->title("Sinkron selesai: {$created} dibuat, {$updated} diperbarui, {$skipped} dilewati (tanpa NIP).")
                        ->success()
                        ->send();
                })
                ->requiresConfirmation(),

            Action::make('lihat-publik')
                ->label('Lihat Halaman Publik')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->color('info')
                ->url(fn () => route('pegawai.index'), true)
                ->openUrlInNewTab(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            PegawaiWidget::class,
        ];
    }
}