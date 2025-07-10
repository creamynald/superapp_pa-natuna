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

    public static function canCreate(): bool
    {
        return false; // karena semua input lewat header action
    }
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
                Tables\Columns\TextColumn::make('jurnalPerkara.nomor_perkara')
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
                // FilamentExportBulkAction::make('export')
                // ->label('Export Data Saksi')
                // ->color('primary')
                // ->defaultFormat('pdf')
                // ->extraViewData(fn () => [
                //     'grouped' => static::getDataGroupedByCase(),
                // ]),
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
}
