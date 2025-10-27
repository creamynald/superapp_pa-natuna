<?php

namespace App\Filament\Resources\Kepaniteraan;

use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use App\Filament\Resources\Kepaniteraan\BerkasPerkaraResource\Pages\ListBerkasPerkaras;
use App\Filament\Resources\Kepaniteraan\BerkasPerkaraResource\Pages\CreateBerkasPerkara;
use App\Filament\Resources\Kepaniteraan\BerkasPerkaraResource\Pages\EditBerkasPerkara;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Utilities\Get;
use App\Filament\Resources\Kepaniteraan\BerkasPerkaraResource\Pages;
use App\Filament\Resources\Kepaniteraan\BerkasPerkaraResource\RelationManagers;
use App\Models\Kepaniteraan\BerkasPerkara;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use App\Models\Kepaniteraan\JurnalPerkara;

class BerkasPerkaraResource extends Resource
{
    protected static string | \UnitEnum | null $navigationGroup = 'Kepaniteraan';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Arsip Perkara';

    protected static ?string $label = 'Arsip Perkara';
    protected static ?string $pluralLabel = 'Arsip Perkara';

    protected static ?string $model = BerkasPerkara::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
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
                DatePicker::make('tanggal_masuk')
                    ->label('Tanggal Arsip')
                    ->required(),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'tersedia' => 'Tersedia',
                        'dipinjam' => 'Dipinjam',
                    ])
                    ->default('tersedia'),
                TextInput::make('lokasi')
                    ->label('Lokasi')
                    ->nullable()
                    ->maxLength(255),
                    ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('jurnalPerkara.nomor_perkara')
                    ->label('Nomor Perkara')
                    ->searchable(),
                TextColumn::make('status')
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
                TextColumn::make('tanggal_masuk')
                    ->label('Tanggal Arsip')
                    ->date(),
            ])->filters([
                SelectFilter::make('status')
                    ->options([
                        'tersedia' => 'Tersedia',
                        'dipinjam' => 'Dipinjam',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
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
            'index' => ListBerkasPerkaras::route('/'),
            'create' => CreateBerkasPerkara::route('/create'),
            'edit' => EditBerkasPerkara::route('/{record}/edit'),
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
