<?php

namespace App\Filament\Resources\Kepaniteraan;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Kepaniteraan\PeminjamanBerkasPerkaraResource\Pages\ListPeminjamanBerkasPerkaras;
use App\Filament\Resources\Kepaniteraan\PeminjamanBerkasPerkaraResource\Pages\CreatePeminjamanBerkasPerkara;
use App\Filament\Resources\Kepaniteraan\PeminjamanBerkasPerkaraResource\Pages\EditPeminjamanBerkasPerkara;
use App\Filament\Resources\Kepaniteraan\PeminjamanBerkasPerkaraResource\RelationManagers\PeminjamanRelationManager;
use App\Filament\Resources\Kepaniteraan\PeminjamanBerkasPerkaraResource\Pages;
use App\Filament\Resources\Kepaniteraan\PeminjamanBerkasPerkaraResource\RelationManagers;
use App\Models\Kepaniteraan\PeminjamanBerkasPerkara;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class PeminjamanBerkasPerkaraResource extends Resource
{
    protected static string | \UnitEnum | null $navigationGroup = 'Kepaniteraan';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Peminjaman Berkas Perkara';
    protected static ?string $model = PeminjamanBerkasPerkara::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationParentItem = 'Arsip Perkara';
    protected static ?string $label = 'Peminjaman';
    protected static ?string $pluralLabel = 'Peminjaman';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('berkas_perkara_id')
                ->label('Berkas Perkara')
                ->relationship('berkas', 'nomor_perkara')
                ->required()
                ->searchable()
                ->preload(),

            // Hidden field untuk user_id
            Hidden::make('user_id')
                ->default(Auth::id()),

            // Placeholder untuk menampilkan nama peminjam (opsional)
            Placeholder::make('peminjam')
                ->label('Peminjam')
                ->content(fn ($record): string => $record?->user?->name ?? Auth::user()?->name ?? '-')
                ->visibleOn(['view', 'edit']),

            TextInput::make('keperluan')
                ->label('Keperluan')
                ->required(),

            DatePicker::make('tanggal_pinjam')
                ->label('Tanggal Pinjam')
                ->default(now())
                ->required(),

            DatePicker::make('tanggal_kembali')
                ->label('Tanggal Kembali'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('berkas.nomor_perkara')
                    ->label('Nomor Perkara')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('Peminjam')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tanggal_pinjam')
                    ->date()
                    ->label('Tanggal Pinjam')
                    ->sortable(),

                TextColumn::make('tanggal_kembali')
                    ->date()
                    ->label('Tanggal Kembali')
                    ->sortable(),

                TextColumn::make('keperluan')
                    ->label('Keperluan'),
            ])
            ->filters([
                //
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
            'index' => ListPeminjamanBerkasPerkaras::route('/'),
            'create' => CreatePeminjamanBerkasPerkara::route('/create'),
            'edit' => EditPeminjamanBerkasPerkara::route('/{record}/edit'),
        ];
    }

    public static function relationManagers(): array
    {
        return [
            PeminjamanRelationManager::class,
        ];
    }
}
