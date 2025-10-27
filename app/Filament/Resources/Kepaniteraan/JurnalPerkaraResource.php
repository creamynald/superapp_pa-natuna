<?php

namespace App\Filament\Resources\Kepaniteraan;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\BulkActionGroup;
use App\Filament\Resources\Kepaniteraan\JurnalPerkaraResource\Pages\ListJurnalPerkaras;
use App\Filament\Resources\Kepaniteraan\JurnalPerkaraResource\Pages\EditJurnalPerkara;
use App\Filament\Resources\Kepaniteraan\JurnalPerkaraResource\Pages;
use App\Filament\Resources\Kepaniteraan\JurnalPerkaraResource\RelationManagers;
use App\Models\Kepaniteraan\JurnalPerkara;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\Kepaniteraan\JurnalPerkaraResource\Widgets\JurnalPerkaraWidget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class JurnalPerkaraResource extends Resource
{
    protected static ?string $model = JurnalPerkara::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';
    protected static string | \UnitEnum | null $navigationGroup = 'Kepaniteraan';
    protected static ?int $navigationSort = 1;
    protected static ?string $label = 'Jurnal Perkara';
    protected static ?string $pluralLabel = 'Jurnal Perkara';
    
    public static function getWidgets(): array
    {
        return [
            JurnalPerkaraWidget::class,
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nomor_perkara')
                    ->label('Nomor Perkara')
                    ->required()
                    ->maxLength(255),
                Select::make('klasifikasi_perkara')
                    ->label('Klasifikasi Perkara')
                    ->options([
                        'Cerai Gugat' => 'Cerai Gugat',
                        'Cerai Talak' => 'Cerai Talak',
                        'Penguasaan Anak' => 'Penguasaan Anak',
                        'Pengesahan Perkawinan/Istbat Nikah' => 'Pengesahan Perkawinan/Istbat Nikah',
                        'Pembatalan Perkawinan' => 'Pembatalan Perkawinan',
                        'Kewarisan' => 'Kewarisan',
                        'Dispensasi Kawin' => 'Dispensasi Kawin',
                        'P3HP/Penetapan Ahli Waris' => 'P3HP/Penetapan Ahli Waris',
                        'Perwalian' => 'Perwalian',
                        'Perubahan Akta' => 'Perubahan Akta',
                    ])
                    ->required(),
                TextInput::make('penggugat')
                    ->label('Penggugat')
                    ->required()
                    ->maxLength(255),
                TextInput::make('tergugat')
                    ->label('Tergugat')
                    ->required()
                    ->maxLength(255),
                TextInput::make('proses_terakhir')
                    ->label('Proses Terakhir')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nomor_perkara')
                    ->label('Nomor Perkara')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('klasifikasi_perkara')
                    ->label('Klasifikasi Perkara')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'Cerai Gugat' => 'primary',
                        'Cerai Talak' => 'danger',
                        'Penguasaan Anak' => 'success',
                        'Pengesahan Perkawinan/Istbat Nikah' => 'warning',
                        'Pembatalan Perkawinan' => 'danger',
                        'Dispensasi Kawin' => 'info',
                        'Perwalian' => 'info',
                        'P3HP/Penetapan Ahli Waris' => 'info',
                        'Kewarisan' => 'info',
                    })
                    ->icon(fn ($state): string => match ($state) {
                        'P3HP/Penetapan Ahli Waris' => 'heroicon-m-users',
                        'Perwalian' => 'heroicon-m-users',
                        'Perubahan Akta' => 'heroicon-m-pencil-square',
                        'Pembatalan Perkawinan' => 'heroicon-m-minus-circle',
                        'Kewarisan' => 'heroicon-m-gift',
                        'Dispensasi Kawin' => 'heroicon-m-users',
                        'Cerai Gugat' => 'heroicon-m-users',
                        'Cerai Talak' => 'heroicon-m-users',
                        'Penguasaan Anak' => 'heroicon-m-user',
                        'Pengesahan Perkawinan/Istbat Nikah' => 'heroicon-m-heart',
                        'Pembatalan Perkawinan' => 'heroicon-m-minus-circle',
                        'Kewarisan' => 'heroicon-m-gift',
                        default => 'heroicon-m-document-text',
                    }),
                TextColumn::make('penggugat')
                    ->label('Penggugat')
                    ->limit(25),
                TextColumn::make('tergugat')
                    ->label('Tergugat'),
                TextColumn::make('proses_terakhir')
                    ->label('Proses Terakhir'),
            ])
            ->modifyQueryUsing(fn ($query) => $query->latestPerkara())
            ->filters([
                SelectFilter::make('klasifikasi_perkara')
                    ->options([
                        'Cerai Gugat' => 'Cerai Gugat',
                        'Cerai Talak' => 'Cerai Talak',
                        'Penguasaan Anak' => 'Penguasaan Anak',
                        'Pengesahan Perkawinan/Istbat Nikah' => 'Pengesahan Perkawinan/Istbat Nikah',
                        'Pembatalan Perkawinan' => 'Pembatalan Perkawinan',
                        'Kewarisan' => 'Kewarisan',
                    ]),
                    // filter tahun from 'nomor_perkara' etc. 107/Pdt.G/2025/PA.Ntn get 2025
                SelectFilter::make('nomor_perkara')
                    ->label('Tahun Perkara')
                    ->options(function () {
                        return JurnalPerkara::query()
                            ->selectRaw("SUBSTRING_INDEX(SUBSTRING_INDEX(nomor_perkara, '/', 3), '/', -1) as tahun")
                            ->distinct()
                            ->pluck('tahun', 'tahun')
                            ->filter()
                            ->sortDesc()
                            ->toArray();
                    })
                    ->default(Carbon::now()->year)
                    ->modifyQueryUsing(fn ($query, $data) => $query->whereRaw(
                        "SUBSTRING_INDEX(SUBSTRING_INDEX(nomor_perkara, '/', 3), '/', -1) = ?",
                        [$data]
                    )),
                
            ])
            ->recordActions([
                // Tables\Actions\EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
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
            'index' => ListJurnalPerkaras::route('/'),
            // 'create' => Pages\CreateJurnalPerkara::route('/create'),
            'edit' => EditJurnalPerkara::route('/{record}/edit'),
        ];
    }
}
