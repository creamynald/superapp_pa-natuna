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
                // TextInput::make('nomor_perkara')
                //     ->label('Nomor Perkara')
                //     ->disabled()
                //     ->dehydrated()
                //     ->required()
                //     ->unique(ignoreRecord: true),
                // Placeholder sebagai highlight nomor perkara
            Placeholder::make('highlight_nomor_perkara')
                ->label('')
                ->content(fn (Get $get) => new HtmlString("
                    <div style='
                        background-color: #16a44d; 
                        color: white; 
                        padding: 1rem; 
                        font-size: 1.25rem; 
                        font-weight: bold; 
                        border-radius: 0.5rem;
                        text-align: center;
                        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                    '>
                        Nomor Perkara: <br>
                        {$get('nomor_perkara')}
                    </div>
                "))
                ->columnSpanFull(), // full width
            // Hidden input untuk simpan data
            TextInput::make('nomor_perkara')
                ->label('Nomor Perkara')
                ->dehydrated()
                ->required()
                ->unique(ignoreRecord: true)
                ->hidden(),
                Grid::make(3)
                    ->schema([
                        TextInput::make('nomor_urut')
                            ->label('Nomor Perkara')
                            ->numeric()
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, Get $get) => self::updateNomorPerkara($set, $get)),

                        Select::make('jenis_perkara')
                            ->label('Jenis Perkara')
                            ->options([
                                'Pdt.G' => 'Pdt.G',
                                'Pdt.P' => 'Pdt.P',
                            ])
                            ->default('Pdt.G')
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn (Set $set, Get $get) => self::updateNomorPerkara($set, $get)),

                        TextInput::make('tahun_perkara')
                            ->label('Tahun')
                            ->default(date('Y'))
                            ->numeric()
                            ->minLength(4)
                            ->maxLength(4)
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, Get $get) => self::updateNomorPerkara($set, $get)),
                    ]),
                Forms\Components\TextInput::make('penggugat')
                    ->label('Penggugat')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('tergugat')
                    ->label('Tergugat')
                    ->required()
                    ->maxLength(255),
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
                Tables\Columns\TextColumn::make('nomor_perkara')
                    ->label('Nomor Perkara')
                    ->searchable()
                    ->sortable(),
                // Tables\Columns\TextColumn::make('penggugat')
                //     ->label('Penggugat')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('tergugat')
                //     ->label('Tergugat')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('tanggal_masuk')
                    ->label('Tanggal Arsip')
                    ->date(),
                Tables\Columns\TextColumn::make('lokasi')   
                    ->label('Lokasi'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'tersedia' => 'success',
                        'dipinjam' => 'danger',
                        
                    })
                    ->sortable(),
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
