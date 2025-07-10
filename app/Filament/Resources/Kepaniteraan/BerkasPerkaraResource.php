<?php

namespace App\Filament\Resources\Kepaniteraan;

use App\Filament\Resources\Kepaniteraan\BerkasPerkaraResource\Pages;
use App\Filament\Resources\Kepaniteraan\BerkasPerkaraResource\RelationManagers;
use App\Models\Kepaniteraan\BerkasPerkara;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use App\Models\Kepaniteraan\JurnalPerkara;

class BerkasPerkaraResource extends Resource
{
    protected static ?string $navigationGroup = 'Kepaniteraan';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Arsip Perkara';

    protected static ?string $label = 'Arsip Perkara';
    protected static ?string $pluralLabel = 'Arsip Perkara';

    protected static ?string $model = BerkasPerkara::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('jurnal_perkara_id')
                    ->label('Nomor Perkara')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->options(function (callable $get) {
                        return JurnalPerkara::latestPerkara()
                            ->when($get('jenis_perkara'), function (Builder $query, $jenisPerkara) {
                                return $query->where('klasifikasi_perkara', $jenisPerkara);
                            })
                            ->when($get('tahun_perkara'), function (Builder $query, $tahunPerkara) {
                                return $query->whereYear('created_at', $tahunPerkara);
                            })
                            ->pluck('nomor_perkara', 'id');
                    })
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set) => $set('dari_pihak', null)), // reset dari_pihak jika ganti perkara
                Forms\Components\DatePicker::make('tanggal_masuk')
                    ->label('Tanggal Arsip')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'tersedia' => 'Tersedia',
                        'dipinjam' => 'Dipinjam',
                    ])
                    ->default('tersedia'),
                Forms\Components\TextInput::make('lokasi')
                    ->label('Lokasi')
                    ->nullable()
                    ->maxLength(255),
                    ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('jurnalPerkara.nomor_perkara')
                    ->label('Nomor Perkara')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'tersedia' => 'success',
                        'dipinjam' => 'warning',
                    })
                    ->icon(fn (string $state): ?string => match ($state) {
                        'tersedia' => 'heroicon-o-check-circle',
                        'dipinjam' => 'heroicon-o-exclamation-circle',
                    })
                    ->formatStateUsing(function ($state, $record) {
                        if ($state === 'tersedia') {
                            return "Tersedia\nLokasi: {$record->lokasi}";
                        }

                        if ($state === 'dipinjam') {
                            // Asumsikan ada relasi pinjaman atau field seperti `dipinjam_oleh`
                            // Contoh: $record->dipinjam_oleh atau $record->peminjaman->user->name
                            $peminjam = $record->dipinjam_oleh ?? 'User tidak ditemukan'; // Sesuaikan dengan relasi/model kamu
                            return "Dipinjam\nOleh: {$peminjam}";
                        }

                        return $state;
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_masuk')
                    ->label('Tanggal Arsip')
                    ->date(),
            ])->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'tersedia' => 'Tersedia',
                        'dipinjam' => 'Dipinjam',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                    // FilamentExportBulkAction::make('Export')
                    // ->extraViewData([
                    //     'fileName' => 'Laporan Berkas Perkara - Bulan Ini',
                    // ])
                ]),
            ])
            ->headerActions([
                // FilamentExportHeaderAction::make('export')
                //     ->defaultFormat('pdf') // xlsx, csv or pdf
                //     ->disableAdditionalColumns()
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListBerkasPerkaras::route('/'),
            'create' => Pages\CreateBerkasPerkara::route('/create'),
            'edit' => Pages\EditBerkasPerkara::route('/{record}/edit'),
        ];
    }

    public static function updateNomorPerkara(Set $set, Get $get): void
    {
        $nomorUrut = $get('nomor_urut');
        $jenis = $get('jenis_perkara');
        $tahun = $get('tahun_perkara');

        if ($nomorUrut && $jenis && $tahun) {
            $set('nomor_perkara', "$nomorUrut/$jenis/$tahun/PA.Natuna");
        }
    }
}
