<?php

namespace App\Filament\Resources\Kesekretariatan;

use App\Filament\Resources\Kesekretariatan\PegawaiResource\Pages;
use App\Filament\Resources\Kesekretariatan\PegawaiResource\RelationManagers;
use App\Models\Kesekretariatan\Pegawai;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PegawaiResource extends Resource
{
    protected static ?string $model = Pegawai::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Umum';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Data Pegawai';
    protected static ?string $recordTitleAttribute = 'nip';
    protected static ?string $pluralModelLabel = 'Data Pegawai';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Pegawai')
                ->description('Isi data pegawai dengan lengkap dan benar.')
                ->schema([
                    Forms\Components\TextInput::make('nip')
                        ->label('Nomor Induk Pegawai (NIP)')
                        ->required()
                        ->unique(ignoreRecord: true),

                    Forms\Components\TextInput::make('user.name')
                        ->label('Nama Pegawai')
                        ->required()
                        ->maxLength(255)
                        ->reactive()
                        ->afterStateUpdated(function (Forms\Components\TextInput $component, $state) {
                            $component->getRecord()->user->name = $state;
                            $component->getRecord()->user->save();
                        }),

                    Forms\Components\Select::make('pangkat_golongan')
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
                        ])->required(),

                    Forms\Components\Select::make('jabatan')
                        ->label('Jabatan')
                        ->options([
                            'ketua' => 'Ketua',
                            'wakil_ketua' => 'Wakil Ketua',
                            'hakim' => 'Hakim',
                            'panitera' => 'Panitera',
                            'panitera_muda_hukum' => 'Panitera Muda Hukum',
                            'panitera_muda_gugatan' => 'Panitera Muda Gugatan',
                            'panitera_muda_permohonan' => 'Panitera Muda Permohonan',
                            'panitera_pengganti' => 'Panitera Pengganti',
                            'jurus_sita' => 'Jurus Sita',
                            'jurus_sita_pengganti' => 'Jurus Sita Pengganti',
                            'sekretaris' => 'Sekretaris',
                            'kasubbag_umum_keuangan' => 'Kepala Sub Bagian Umum dan Keuangan',
                            'kasubbag_kepegawaian' => 'Kepala Sub Bagian Kepegawaian',
                            'kasubbag_ptip' => 'Kepala Sub Bagian Perencanaan, Teknologi Informasi dan Pelaporan',
                            'staff' => 'Staff',
                            'cpns' => 'Calon Pegawai Negeri Sipil (CPNS)',
                        ])->required(),
                    

                    Forms\Components\TextInput::make('tempat_lahir'),
                    Forms\Components\DatePicker::make('tanggal_lahir'),

                    Forms\Components\DatePicker::make('tmt_golongan'),
                    Forms\Components\DatePicker::make('tmt_pegawai'),

                    Forms\Components\TextInput::make('pendidikan_terakhir'),
                    Forms\Components\TextInput::make('tahun_pendidikan')->numeric()->minValue(1900)->maxValue((int) date('Y')),

                    Forms\Components\DatePicker::make('kgb_yad')
                        ->label('KGB YAD')
                        ->helperText('Kenaikan Gaji Berkala Yang Akan Datang'),
                    Forms\Components\Textarea::make('keterangan'),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nip')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama Pegawai')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.email')->label('Email Akun')->searchable(),
                Tables\Columns\TextColumn::make('jabatan')->searchable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListPegawais::route('/'),
            'create' => Pages\CreatePegawai::route('/create'),
            'edit' => Pages\EditPegawai::route('/{record}/edit'),
        ];
    }
}
