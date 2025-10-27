<?php

namespace App\Filament\Resources\Kesekretariatan;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Kesekretariatan\PegawaiResource\Pages\ListPegawais;
use App\Filament\Resources\Kesekretariatan\PegawaiResource\Pages\CreatePegawai;
use App\Filament\Resources\Kesekretariatan\PegawaiResource\Pages\EditPegawai;
use App\Filament\Resources\Kesekretariatan\PegawaiResource\Pages;
use App\Filament\Resources\Kesekretariatan\PegawaiResource\RelationManagers;
use App\Models\Kesekretariatan\Pegawai;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use RyanChandler\FilamentProgressColumn\ProgressColumn;

class PegawaiResource extends Resource
{
    protected static ?string $model = Pegawai::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-user-group';
    protected static string | \UnitEnum | null $navigationGroup = 'Umum';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Data Pegawai';
    protected static ?string $recordTitleAttribute = 'nip';
    protected static ?string $pluralModelLabel = 'Data Pegawai';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Pegawai')
                ->description('Isi data pegawai dengan lengkap dan benar.')
                ->schema([
                    TextInput::make('nip')
                        ->label('Nomor Induk Pegawai (NIP)')
                        ->required()
                        ->unique(ignoreRecord: true),

                    TextInput::make('user.name')
                        ->label('Nama Pegawai')
                        ->required()
                        ->maxLength(255)
                        ->reactive()
                        ->afterStateUpdated(function ($state, ?\App\Models\Kesekretariatan\Pegawai $record) {
                            if (! $record || ! $record->user) {
                                // Belum ada record/relasi saat create, jangan apa-apa
                                return;
                            }
                            $record->user->update(['name' => $state]);
                        }),

                    Select::make('pangkat_golongan')
                        ->label('Pangkat/Golongan')
                        ->options([
                            'Ia' => 'I/a',
                            'Ib' => 'I/b',
                            'Ic' => 'I/c',
                            'Id' => 'I/d',
                            'IIa' => 'II/a',
                            'IIb' => 'II/b',
                            'IIc' => 'II/c',
                            'IId' => 'II/d',
                            'IIIa' => 'III/a',
                            'IIIb' => 'III/b',
                            'IIIc' => 'III/c',
                            'IIId' => 'III/d',
                            'IVa' => 'IV/a',
                            'IVb' => 'IV/b',
                            'IVc' => 'IV/c',
                            'IVd' => 'IV/d',
                            'IVe' => 'IV/e',
                        ])->required(),

                    Select::make('jabatan')
                        ->label('Jabatan')
                        ->options([
                            'Ketua' => 'Ketua',
                            'Wakil Ketua' => 'Wakil Ketua',
                            'Hakim' => 'Hakim',
                            'Panitera' => 'Panitera',
                            'Panitera Muda Hukum' => 'Panitera Muda Hukum',
                            'Panitera Muda Gugatan' => 'Panitera Muda Gugatan',
                            'Panitera Muda Permohonan' => 'Panitera Muda Permohonan',
                            'Panitera Pengganti' => 'Panitera Pengganti',
                            'Jurus Sita' => 'Jurus Sita',
                            'Jurus Sita Pengganti' => 'Jurus Sita Pengganti',
                            'Sekretaris' => 'Sekretaris',
                            'Kepala Sub Bagian Umum dan Keuangan' => 'Kepala Sub Bagian Umum dan Keuangan',
                            'Kepala Sub Bagian Kepegawaian' => 'Kepala Sub Bagian Kepegawaian',
                            'Kepala Sub Bagian Perencanaan, Teknologi Informasi dan Pelaporan' => 'Kepala Sub Bagian Perencanaan, Teknologi Informasi dan Pelaporan',
                            'Staff' => 'Staff',
                            'Calon Pegawai Negeri Sipil (CPNS)' => 'Calon Pegawai Negeri Sipil (CPNS)',
                            'P3K' => 'Pegawai Pemerintah dengan Perjanjian Kerja (P3K)',
                            'PPNPN' => 'Pegawai Pemerintah Non Pegawai Negeri (PPNPN)',
                            'Pramubakti' => 'Pramubakti',
                        ])->required(),
                    

                    TextInput::make('tempat_lahir'),
                    DatePicker::make('tanggal_lahir'),

                    DatePicker::make('tmt_golongan'),
                    DatePicker::make('tmt_pegawai'),

                    TextInput::make('pendidikan_terakhir'),
                    TextInput::make('tahun_pendidikan')->numeric()->minValue(1900)->maxValue((int) date('Y')),

                    DatePicker::make('kgb_yad')
                        ->label('KGB YAD')
                        ->helperText('Kenaikan Gaji Berkala Yang Akan Datang'),
                    Textarea::make('keterangan'),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nip')->searchable()->sortable(),
                TextColumn::make('user.name')
                    ->label('Nama Pegawai')
                    ->searchable()
                    ->sortable(),
                // Tables\Columns\TextColumn::make('user.email')->label('Email Akun')->searchable(),
                TextColumn::make('jabatan')->searchable(),
                // Tables\Columns\TextColumn::make('created_at')->dateTime(),
                ProgressColumn::make('progress_kelengkapan')
                    ->label('Kelengkapan')
                    ->color(fn ($state) => match (true) {
                        $state < 60  => 'danger',   // merah (marah)
                        $state < 80  => 'warning',  // kuning
                        $state >= 90 => 'success',  // hijau
                        default      => 'primary',  // default (biru)
                    })
                    ->sortable(false)
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPegawais::route('/'),
            'create' => CreatePegawai::route('/create'),
            'edit' => EditPegawai::route('/{record}/edit'),
        ];
    }
}
