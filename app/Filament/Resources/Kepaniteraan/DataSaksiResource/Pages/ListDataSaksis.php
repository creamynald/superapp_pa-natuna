<?php

namespace App\Filament\Resources\Kepaniteraan\DataSaksiResource\Pages;

use App\Filament\Resources\Kepaniteraan\DataSaksiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Actions\Action;
use App\Models\Kepaniteraan\DataSaksi;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\TextSelect;
use App\Models\Kepaniteraan\JurnalPerkara;

class ListDataSaksis extends ListRecords
{
    protected static string $resource = DataSaksiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
            Actions\Action::make('Tambah Data Saksi')
                ->icon('heroicon-o-plus-circle')
                ->color('primary')
                ->steps([
                    Step::make('Detail Perkara')
                        ->description('Masukkan informasi dasar')
                        ->schema([
                            Select::make('jurnal_perkara_id')
                                ->label('Nomor Perkara')
                                ->required()
                                ->helperText('Contoh: 123/Pdt.(P/G)/2025/PA.Ntn')
                                ->searchable()
                                ->preload()
                                ->options(function (callable $get) {
                                    return JurnalPerkara::latestPerkara()
                                        ->search($get('jurnal_perkara_id'))
                                        ->pluck('nomor_perkara', 'id')
                                        ->toArray();
                                })
                                ->reactive()
                                ->afterStateUpdated(fn (callable $set) => $set('dari_pihak', null)), // reset dari_pihak jika ganti perkara

                            Select::make('dari_pihak')
                                ->label('Dari Pihak')
                                ->required()
                                ->placeholder('Pilih Penggugat/Tergugat')
                                ->helperText('Pilih pihak mana yang akan diinputkan saksi')
                                ->options(function (callable $get) {
                                    $jurnalPerkaraId = $get('jurnal_perkara_id');
                                    if (!$jurnalPerkaraId) {
                                        return [];
                                    }

                                    $jurnalPerkara = JurnalPerkara::find($jurnalPerkaraId);
                                    if (!$jurnalPerkara) {
                                        return [];
                                    }

                                    return //nama penggugat dan tergugat
                                        [
                                            'Penggugat' => $jurnalPerkara->penggugat,
                                            'Tergugat' => $jurnalPerkara->tergugat,
                                        ];
                                })
                                ->default('Penggugat'),
                        ])->columns(2),

                    Step::make('Data Saksi 1')
                        ->description('Masukkan Data Saksi 1')
                        ->schema([
                            Grid::make()
                                ->schema([
                                    TextInput::make('nik')
                                        ->helperText('Nomor Induk Kependudukan (NIK)')
                                        ->label('NIK')
                                        ->required()
                                        ->maxLength(16)
                                        ->numeric(),
                                    TextInput::make('nama_lengkap')
                                        ->helperText('Nama Lengkap Sesuai KTP')
                                        ->label('Nama Lengkap')
                                        ->required()
                                        ->maxLength(255),
                                    TextInput::make('bin_binti')
                                        ->helperText('Nama Ayah Kandung')
                                        ->label('Bin/Binti')
                                        ->required()
                                        ->maxLength(255),
                                ])->columns(3),
                            Textarea::make('alamat')
                                ->helperText('Alamat Lengkap')
                                ->label('Alamat')
                                ->required()
                                ->maxLength(255),
                            Grid::make()
                                ->schema([
                                    TextInput::make('tempat_tanggal_lahir')
                                        ->helperText('Contoh: Natuna, 1 Januari 1990')
                                        ->label('Tempat, Tanggal Lahir')
                                        ->required()
                                        ->maxLength(255),
                                    TextInput::make('email')
                                        ->helperText('Alamat Email')
                                        ->label('Email')
                                        ->required()
                                        ->maxLength(255),
                                    TextInput::make('no_hp')
                                        ->helperText('Nomor Handphone/Whatsapp')
                                        ->label('No. HP')
                                        ->required()
                                        ->maxLength(15)
                                        ->numeric(),
                                ])->columns(3),
                            Grid::make()
                                ->schema([
                                    TextInput::make('rt')
                                        ->helperText('RT (Rukun Tetangga)')
                                        ->label('RT')
                                        ->required()
                                        ->maxLength(3)
                                        ->numeric(),
                                    TextInput::make('rw')
                                        ->helperText('RW (Rukun Warga)')
                                        ->label('RW')
                                        ->required()
                                        ->maxLength(3)
                                        ->numeric(),
                                ])->columns(2),
                            Grid::make()
                                ->schema([
                                    TextInput::make('kelurahan')
                                        ->helperText('Nama Kelurahan')
                                        ->label('Kelurahan')
                                        ->required()
                                        ->maxLength(255),
                                    TextInput::make('kecamatan')
                                        ->helperText('Nama Kecamatan')
                                        ->label('Kecamatan')
                                        ->required()
                                        ->maxLength(255),
                                    TextInput::make('kabupaten')
                                        ->helperText('Nama Kabupaten/Kota')
                                        ->label('Kabupaten/Kota')
                                        ->required()
                                        ->maxLength(255),
                                    TextInput::make('provinsi')
                                        ->helperText('Nama Provinsi')
                                        ->label('Provinsi')
                                        ->required()
                                        ->maxLength(255),
                                ])->columns(4),
                            Grid::make()
                                ->schema([
                                    Select::make('jenis_kelamin')
                                        ->helperText('Jenis Kelamin Saksi 1')
                                        ->label('Jenis Kelamin')
                                        ->options([
                                            'Laki-laki' => 'Laki-laki',
                                            'Perempuan' => 'Perempuan',
                                        ])
                                        ->default('Laki-laki')
                                        ->required(),
                                    TextInput::make('pekerjaan')
                                        ->helperText('Pekerjaan Saksi')
                                        ->label('Pekerjaan')
                                        ->required()
                                        ->maxLength(255),
                                ])->columns(2),
                            Grid::make()
                                ->schema([
                                    Select::make('pendidikan')
                                        ->helperText('Pendidikan Terakhir Saksi')
                                        ->label('Pendidikan')
                                        ->options([
                                            'Tidak Sekolah' => 'Tidak Sekolah',
                                            'SD' => 'SD',
                                            'SLTP' => 'SLTP',
                                            'SLTA' => 'SLTA',
                                            'Diploma Satu (D1)' => 'Diploma Satu (D1)',
                                            'Diploma Dua (D2)' => 'Diploma Dua (D2)',
                                            'Diploma Tiga (D3)' => 'Diploma Tiga (D3)',
                                            'Sarjana (S1)' => 'Sarjana (S1)',
                                            'Magister (S2)' => 'Magister (S2)',
                                            'Doktor (S3)' => 'Doktor (S3)',
                                        ])
                                        ->default('Tidak Sekolah')
                                        ->required(),
                                    Select::make('agama')
                                        ->helperText('Agama Saksi')
                                        ->label('Agama')
                                        ->options([
                                            'Islam' => 'Islam',
                                            'Kristen' => 'Kristen',
                                            'Katolik' => 'Katolik',
                                            'Hindu' => 'Hindu',
                                            'Buddha' => 'Buddha',
                                            'Konghucu' => 'Konghucu',
                                            'Lainnya' => 'Lainnya',
                                        ])
                                        ->default('Islam')
                                        ->required(),
                                    Select::make('status_kawin')
                                        ->helperText('Status Perkawinan Saksi')
                                        ->label('Status Kawin')
                                        ->options([
                                            'Kawin' => 'Kawin',
                                            'Belum Kawin' => 'Belum Kawin',
                                            'Duda' => 'Duda',
                                            'Janda' => 'Janda',
                                        ])
                                        ->default('Belum Kawin')
                                        ->required(),
                                    Select::make('hubungan_dengan_penggugat_tergugat')
                                        ->helperText('Hubungan anda (Saksi 1) dengan Penggugat/Tergugat')
                                        ->label('Hubungan dengan P/T')
                                        ->options([
                                            'Ayah' => 'Ayah',
                                            'Ibu' => 'Ibu',
                                            'Adik' => 'Adik',
                                            'Kakak' => 'Kakak',
                                            'Paman' => 'Paman',
                                            'Bibi' => 'Bibi',
                                            'Saudara' => 'Saudara',
                                            'Lainnya' => 'Lainnya',
                                        ])
                                        ->default('Lainnya')
                                        ->required(),
                                ])->columns(4),
                            Select::make('pernah_lihat_bertengkar')
                                ->helperText('Pertanyaan khusus perkara Cerai Gugat/Cerai Talak')
                                ->label('Apakah Saksi Pernah Melihat/Mendengar Penggugat & Tergugat Bertengkar?')
                                ->options([
                                    'Ya' => 'Ya',
                                    'Tidak' => 'Tidak',
                                ])
                                ->default('Tidak')
                                ->required(),
                            Select::make('status_pisah_rumah')
                                ->helperText('Pertanyaan khusus perkara Cerai Gugat/Cerai Talak')
                                ->label('Apakah Penggugat & Tergugat Pisah Rumah?')
                                ->options([
                                    'Ya' => 'Ya',
                                    'Tidak' => 'Tidak',
                                ])
                                ->default('Tidak')
                                ->required(),
                        ]),

                    Step::make('Data Saksi 2')
                        ->description('Masukkan Data Saksi 2')
                        ->schema([
                            Grid::make()
                                ->schema([
                                    TextInput::make('nik2')
                                        ->helperText('Nomor Induk Kependudukan (NIK)')
                                        ->label('NIK')
                                        ->required()
                                        ->maxLength(16)
                                        ->numeric(),
                                    TextInput::make('nama_lengkap2')
                                        ->helperText('Nama Lengkap Sesuai KTP')
                                        ->label('Nama Lengkap')
                                        ->required()
                                        ->maxLength(255),
                                    TextInput::make('bin_binti2')
                                        ->helperText('Nama Ayah Kandung')
                                        ->label('Bin/Binti')
                                        ->required()
                                        ->maxLength(255),
                                ])->columns(3),
                            Textarea::make('alamat2')
                                ->helperText('Alamat Lengkap')
                                ->label('Alamat')
                                ->required()
                                ->maxLength(255),
                            Grid::make()
                                ->schema([
                                    TextInput::make('tempat_tanggal_lahir2')
                                        ->helperText('Contoh: Natuna, 1 Januari 1990')
                                        ->label('Tempat, Tanggal Lahir')
                                        ->required()
                                        ->maxLength(255),
                                    TextInput::make('email2')
                                        ->helperText('Alamat Email')
                                        ->label('Email')
                                        ->required()
                                        ->maxLength(255),
                                    TextInput::make('no_hp2')
                                        ->helperText('Nomor Handphone/Whatsapp')
                                        ->label('No. HP')
                                        ->required()
                                        ->maxLength(15)
                                        ->numeric(),
                                ])->columns(3),
                            Grid::make()
                                ->schema([
                                    TextInput::make('rt2')
                                        ->helperText('RT (Rukun Tetangga)')
                                        ->label('RT')
                                        ->required()
                                        ->maxLength(3)
                                        ->numeric(),
                                    TextInput::make('rw2')
                                        ->helperText('RW (Rukun Warga)')
                                        ->label('RW')
                                        ->required()
                                        ->maxLength(3)
                                        ->numeric(),
                                ])->columns(2),
                            Grid::make()
                                ->schema([
                                    TextInput::make('kelurahan2')
                                        ->helperText('Nama Kelurahan')
                                        ->label('Kelurahan')
                                        ->required()
                                        ->maxLength(255),
                                    TextInput::make('kecamatan2')
                                        ->helperText('Nama Kecamatan')
                                        ->label('Kecamatan')
                                        ->required()
                                        ->maxLength(255),
                                    TextInput::make('kabupaten2')
                                        ->helperText('Nama Kabupaten/Kota')
                                        ->label('Kabupaten/Kota')
                                        ->required()
                                        ->maxLength(255),
                                    TextInput::make('provinsi2')
                                        ->helperText('Nama Provinsi')
                                        ->label('Provinsi')
                                        ->required()
                                        ->maxLength(255),
                                ])->columns(4),
                            Grid::make()
                                ->schema([
                                    Select::make('jenis_kelamin2')
                                        ->helperText('Jenis Kelamin Saksi 2')
                                        ->label('Jenis Kelamin')
                                        ->options([
                                            'Laki-laki' => 'Laki-laki',
                                            'Perempuan' => 'Perempuan',
                                        ])
                                        ->default('Laki-laki')
                                        ->required(),
                                    TextInput::make('pekerjaan2')
                                        ->helperText('Pekerjaan Saksi')
                                        ->label('Pekerjaan')
                                        ->required()
                                        ->maxLength(255),
                                ])->columns(2),
                            Grid::make()
                                ->schema([
                                    Select::make('pendidikan2')
                                        ->helperText('Pendidikan Terakhir Saksi')
                                        ->label('Pendidikan')
                                        ->options([
                                            'Tidak Sekolah' => 'Tidak Sekolah',
                                            'SD' => 'SD',
                                            'SLTP' => 'SLTP',
                                            'SLTA' => 'SLTA',
                                            'Diploma Satu (D1)' => 'Diploma Satu (D1)',
                                            'Diploma Dua (D2)' => 'Diploma Dua (D2)',
                                            'Diploma Tiga (D3)' => 'Diploma Tiga (D3)',
                                            'Sarjana (S1)' => 'Sarjana (S1)',
                                            'Magister (S2)' => 'Magister (S2)',
                                            'Doktor (S3)' => 'Doktor (S3)',
                                        ])
                                        ->default('Tidak Sekolah')
                                        ->required(),
                                    Select::make('agama2')
                                        ->helperText('Agama Saksi')
                                        ->label('Agama')
                                        ->options([
                                            'Islam' => 'Islam',
                                            'Kristen' => 'Kristen',
                                            'Katolik' => 'Katolik',
                                            'Hindu' => 'Hindu',
                                            'Buddha' => 'Buddha',
                                            'Konghucu' => 'Konghucu',
                                            'Lainnya' => 'Lainnya',
                                        ])
                                        ->default('Islam')
                                        ->required(),
                                    Select::make('status_kawin2')
                                        ->helperText('Status Perkawinan Saksi')
                                        ->label('Status Kawin')
                                        ->options([
                                            'Kawin' => 'Kawin',
                                            'Belum Kawin' => 'Belum Kawin',
                                            'Duda' => 'Duda',
                                            'Janda' => 'Janda',
                                        ])
                                        ->default('Belum Kawin')
                                        ->required(),
                                    Select::make('hubungan_dengan_penggugat_tergugat2')
                                        ->helperText('Hubungan anda (Saksi 2) dengan Penggugat/Tergugat')
                                        ->label('Hubungan dengan P/T')
                                        ->options([
                                            'Ayah' => 'Ayah',
                                            'Ibu' => 'Ibu',
                                            'Adik' => 'Adik',
                                            'Kakak' => 'Kakak',
                                            'Paman' => 'Paman',
                                            'Bibi' => 'Bibi',
                                            'Saudara' => 'Saudara',
                                            'Lainnya' => 'Lainnya',
                                        ])
                                        ->default('Lainnya')
                                        ->required(),
                                ])->columns(4),
                            Select::make('pernah_lihat_bertengkar2')
                                ->helperText('Pertanyaan khusus perkara Cerai Gugat/Cerai Talak')
                                ->label('Apakah Saksi Pernah Melihat/Mendengar Penggugat & Tergugat Bertengkar?')
                                ->options([
                                    'Ya' => 'Ya',
                                    'Tidak' => 'Tidak',
                                ])
                                ->default('Tidak')
                                ->required(),
                            Select::make('status_pisah_rumah2')
                                ->helperText('Pertanyaan khusus perkara Cerai Gugat/Cerai Talak')
                                ->label('Apakah Penggugat & Tergugat Pisah Rumah?')
                                ->options([
                                    'Ya' => 'Ya',
                                    'Tidak' => 'Tidak',
                                ])
                                ->default('Tidak')
                                ->required(),
                        ]),
                ])
                ->action(function (array $data) {
                    // Ambil jurnal_perkara_id dan dari_pihak dari step pertama
                    $nomorPerkara = $data['jurnal_perkara_id'];
                    $dariPihak = $data['dari_pihak'];

                    // Simpan Saksi 1
                    \App\Models\Kepaniteraan\DataSaksi::create([
                        'jurnal_perkara_id' => $nomorPerkara,
                        'dari_pihak' => $dariPihak,
                        'nik' => $data['nik'],
                        'nama_lengkap' => $data['nama_lengkap'],
                        'bin_binti' => $data['bin_binti'],
                        // force saksi_ke to 1
                        'saksi_ke' => 1,
                        'alamat' => $data['alamat'],
                        'tempat_tanggal_lahir' => $data['tempat_tanggal_lahir'],
                        'email' => $data['email'] ?? null,
                        'no_hp' => $data['no_hp'] ?? null,
                        'rt' => $data['rt'] ?? null,
                        'rw' => $data['rw'] ?? null,
                        'kelurahan' => $data['kelurahan'] ?? null,
                        'kecamatan' => $data['kecamatan'] ?? null,
                        'kabupaten' => $data['kabupaten'] ?? null,
                        'provinsi' => $data['provinsi'] ?? null,
                        'jenis_kelamin' => $data['jenis_kelamin'] ?? null,
                        'pekerjaan' => $data['pekerjaan'] ?? null,
                        'pendidikan' => $data['pendidikan'] ?? null,
                        'agama' => $data['agama'] ?? null,
                        'status_kawin' => $data['status_kawin'] ?? null,
                        'hubungan_dengan_penggugat_tergugat' => $data['hubungan_dengan_penggugat_tergugat'] ?? null,
                        'pernah_lihat_bertengkar' => $data['pernah_lihat_bertengkar'] ?? null,
                        'status_pisah_rumah' => $data['status_pisah_rumah'] ?? null,
                    ]);

                    // Simpan Saksi 2 (field sama, tapi nilai berbeda)
                    \App\Models\Kepaniteraan\DataSaksi::create([
                        'jurnal_perkara_id' => $nomorPerkara,
                        'dari_pihak' => $dariPihak,
                        'nik' => $data['nik2'],
                        'nama_lengkap' => $data['nama_lengkap2'],
                        'bin_binti' => $data['bin_binti2'],
                        // force saksi_ke to 2
                        'saksi_ke' => 2,
                        'alamat' => $data['alamat2'],
                        'tempat_tanggal_lahir' => $data['tempat_tanggal_lahir2'],
                        'email' => $data['email2'] ?? null,
                        'no_hp' => $data['no_hp2'] ?? null,
                        'rt' => $data['rt2'] ?? null,
                        'rw' => $data['rw2'] ?? null,
                        'kelurahan' => $data['kelurahan2'] ?? null,
                        'kecamatan' => $data['kecamatan2'] ?? null,
                        'kabupaten' => $data['kabupaten2'] ?? null,
                        'provinsi' => $data['provinsi2'] ?? null,
                        'jenis_kelamin' => $data['jenis_kelamin2'] ?? null,
                        'pekerjaan' => $data['pekerjaan2'] ?? null,
                        'pendidikan' => $data['pendidikan2'] ?? null,
                        'agama' => $data['agama2'] ?? null,
                        'status_kawin' => $data['status_kawin2'] ?? null,
                        'hubungan_dengan_penggugat_tergugat' => $data['hubungan_dengan_penggugat_tergugat2'] ?? null,
                        'pernah_lihat_bertengkar' => $data['pernah_lihat_bertengkar2'] ?? null,
                        'status_pisah_rumah' => $data['status_pisah_rumah2'] ?? null,
                    ]);
                })
                ->requiresConfirmation()
                ->modalHeading('Tambah Data Saksi')
                ->modalSubmitActionLabel('Simpan Data Saksi')
                ->modalCancelActionLabel('Batal')
                ->modalWidth(MaxWidth::FiveExtraLarge)
                ->modalDescription('Isi data saksi dengan lengkap dan benar. Pastikan semua informasi yang diberikan akurat dan sesuai dengan dokumen resmi. Setelah disimpan, data tidak dapat diubah lagi.'), 
        ];
    }
}
