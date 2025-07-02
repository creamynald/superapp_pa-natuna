<?php

namespace App\Filament\Resources\Kepaniteraan;

use App\Filament\Resources\Kepaniteraan\PeminjamanBerkasPerkaraResource\Pages;
use App\Filament\Resources\Kepaniteraan\PeminjamanBerkasPerkaraResource\RelationManagers;
use App\Models\Kepaniteraan\PeminjamanBerkasPerkara;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class PeminjamanBerkasPerkaraResource extends Resource
{
    protected static ?string $navigationGroup = 'Kepaniteraan';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Peminjaman Berkas Perkara';
    protected static ?string $model = PeminjamanBerkasPerkara::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('berkas_perkara_id')
                ->label('Berkas Perkara')
                ->relationship('berkas', 'nomor_perkara')
                ->required()
                ->searchable()
                ->preload(),

            // Hidden field untuk user_id
            Forms\Components\Hidden::make('user_id')
                ->default(Auth::id()),

            // Placeholder untuk menampilkan nama peminjam (opsional)
            Forms\Components\Placeholder::make('peminjam')
                ->label('Peminjam')
                ->content(fn ($record): string => $record?->user?->name ?? Auth::user()?->name ?? '-')
                ->visibleOn(['view', 'edit']),

            Forms\Components\TextInput::make('keperluan')
                ->label('Keperluan')
                ->required(),

            Forms\Components\DatePicker::make('tanggal_pinjam')
                ->label('Tanggal Pinjam')
                ->default(now())
                ->required(),

            Forms\Components\DatePicker::make('tanggal_kembali')
                ->label('Tanggal Kembali'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('berkas.nomor_perkara')
                    ->label('Nomor Perkara')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Peminjam')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal_pinjam')
                    ->date()
                    ->label('Tanggal Pinjam')
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal_kembali')
                    ->date()
                    ->label('Tanggal Kembali')
                    ->sortable(),

                Tables\Columns\TextColumn::make('keperluan')
                    ->label('Keperluan'),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPeminjamanBerkasPerkaras::route('/'),
            'create' => Pages\CreatePeminjamanBerkasPerkara::route('/create'),
            'edit' => Pages\EditPeminjamanBerkasPerkara::route('/{record}/edit'),
        ];
    }

    public static function relationManagers(): array
    {
        return [
            RelationManagers\PeminjamanRelationManager::class,
        ];
    }
}
