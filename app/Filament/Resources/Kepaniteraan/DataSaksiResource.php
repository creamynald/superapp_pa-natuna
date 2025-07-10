<?php

namespace App\Filament\Resources\Kepaniteraan;

use App\Filament\Resources\Kepaniteraan\DataSaksiResource\Pages;
use App\Filament\Resources\Kepaniteraan\DataSaksiResource\RelationManagers;
use App\Models\Kepaniteraan\DataSaksi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;

class DataSaksiResource extends Resource
{
    protected static ?string $model = DataSaksi::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Kepaniteraan';
    protected static ?int $navigationSort = 2;  
    protected static ?string $navigationLabel = 'Data Saksi';

    protected static ?string $navigationParentItem = 'Jurnal Perkara';
    protected static ?string $label = 'Data Saksi';
    protected static ?string $pluralLabel = 'Data Saksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // 
            ])
            ->filters([
                //
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_perkara')
                    ->label('Nomor Perkara')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->label('Nama Lengkap')
                    ->searchable(),
                Tables\Columns\TextColumn::make('alamat')
                    ->label('Alamat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->searchable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Laki-laki' => 'info',
                        'Perempuan' => 'danger',
                        
                    })
            ])
            ->filters([
                // 
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
                // FilamentExportHeaderAction::make(),
                // FilamentExportBulkAction::make('export')
                //     ->label('Export Data Saksi')
                //     ->color('primary')
                //     ->extraViewData([
                //         'dataSaksi' => 'Data Saksi',
                //     ])
                FilamentExportBulkAction::make('export')
                ->label('Export Data Saksi')
                ->color('primary')
                ->defaultFormat('pdf')
                ->extraViewData(fn () => [
                    'grouped' => static::getDataGroupedByCase(),
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
            'index' => Pages\ListDataSaksis::route('/'),
            'create' => Pages\CreateDataSaksi::route('/create'),
            'edit' => Pages\EditDataSaksi::route('/{record}/edit'),
        ];
    }

    private static function getDataForExport()
    {
        $query = \App\Models\Kepaniteraan\DataSaksi::query();

        // Jika ada filter aktif di tabel, ikutkan juga
        if (request()->has('tableFilters')) {
            foreach (request('tableFilters') as $filter) {
                $key = $filter['key'];
                $value = $filter['value'];
                if ($value !== null) {
                    $query->where($key, 'like', "%$value%");
                }
            }
        }

        $results = $query->get();

        $formatted = [];

        // Karena satu perkara punya dua saksi, kita group berdasarkan nomor_perkara
        $grouped = $results->groupBy('nomor_perkara');

        foreach ($grouped as $nomorPerkara => $saksis) {
            $saksiList = $saksis->toArray();
            foreach ($saksiList as $index => $saksi) {
                $formatted[] = [
                    'nomor_perkara' => $saksi['nomor_perkara'],
                    'saksi_nama' => "SAKSI " . ($index + 1),
                    'nik' => $saksi['nik'] ?? '-',
                    'nama_lengkap' => $saksi['nama_lengkap'] ?? '-',
                    'bin_binti' => $saksi['bin_binti'] ?? '-',
                    'tempat_tanggal_lahir' => $saksi['tempat_tanggal_lahir'] ?? '-',
                    'alamat' => $saksi['alamat'] ?? '-',
                    'no_hp' => $saksi['no_hp'] ?? '-',
                    'email' => $saksi['email'] ?? '-',
                    'jenis_kelamin' => $saksi['jenis_kelamin'] ?? '-',
                    'agama' => $saksi['agama'] ?? '-',
                    'pekerjaan' => $saksi['pekerjaan'] ?? '-',
                    'pendidikan' => $saksi['pendidikan'] ?? '-',
                    'status_kawin' => $saksi['status_kawin'] ?? '-',
                    'hubungan_dengan_penggugat_tergugat' => $saksi['hubungan_dengan_penggugat_tergugat'] ?? '-',
                ];
            }
        }

        return $formatted;
    }

    private static function getDataGroupedByCase()
{
    $query = \App\Models\Kepaniteraan\DataSaksi::query();

    // Jika ada filter aktif, ikutkan
    if (request()->has('tableFilters')) {
        foreach (request('tableFilters') as $filter) {
            $key = $filter['key'];
            $value = $filter['value'];
            if ($value !== null) {
                $query->where($key, 'like', "%$value%");
            }
        }
    }

    $results = $query->get();

    // Group by nomor_perkara
    return $results->groupBy('nomor_perkara')->map(function ($items) {
        return $items->map(function ($item) {
            return [
                'nik' => $item->nik,
                'nama_lengkap' => $item->nama_lengkap,
                'bin_binti' => $item->bin_binti,
                'tempat_tanggal_lahir' => $item->tempat_tanggal_lahir,
                'no_hp' => $item->no_hp,
                'email' => $item->email,
                'alamat' => $item->alamat,
                'jenis_kelamin' => $item->jenis_kelamin,
                'agama' => $item->agama,
                'pekerjaan' => $item->pekerjaan,
                'pendidikan' => $item->pendidikan,
                'status_kawin' => $item->status_kawin,
                'hubungan_dengan_penggugat_tergugat' => $item->hubungan_dengan_penggugat_tergugat,
            ];
        })->toArray();
    });
}
}
