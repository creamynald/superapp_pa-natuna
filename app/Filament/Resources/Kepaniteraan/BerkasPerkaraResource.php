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

class BerkasPerkaraResource extends Resource
{
    protected static ?string $navigationGroup = 'Kepaniteraan';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Berkas Perkara';

    protected static ?string $model = BerkasPerkara::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nomor_perkara')
                    ->label('Nomor Perkara')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\TextInput::make('penggugat')
                    ->label('Penggugat')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('tergugat')
                    ->label('Tergugat')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('tanggal_masuk')
                    ->label('Tanggal Masuk')
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
            ])->columns(2)
            ->columns([
                'default' => 1,
                'sm' => 2,
                'md' => 2,
                'lg' => 2,
                'xl' => 2,
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
                Tables\Columns\TextColumn::make('penggugat')
                    ->label('Penggugat')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tergugat')
                    ->label('Tergugat')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_masuk')
                    ->label('Tanggal Masuk')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'tersedia' => 'success',
                        'dipinjam' => 'warning',
                        
                    }),
                Tables\Columns\TextColumn::make('lokasi')
                    ->label('Lokasi'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable(),
            ])->filters([
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
}
