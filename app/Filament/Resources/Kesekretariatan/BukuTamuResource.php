<?php

namespace App\Filament\Resources\Kesekretariatan;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\Filter;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Kesekretariatan\BukuTamuResource\Pages\ListBukuTamus;
use App\Filament\Resources\Kesekretariatan\BukuTamuResource\Pages\CreateBukuTamu;
use App\Filament\Resources\Kesekretariatan\BukuTamuResource\Pages\EditBukuTamu;
use App\Filament\Resources\Kesekretariatan\BukuTamuResource\Pages;
use App\Filament\Resources\Kesekretariatan\BukuTamuResource\RelationManagers;
use App\Models\Kesekretariatan\BukuTamu;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Saade\FilamentAutograph\Forms\Components\SignaturePad;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BukuTamuResource extends Resource
{
    protected static ?string $model = BukuTamu::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-book-open';
    protected static string | \UnitEnum | null $navigationGroup = 'Umum';
    protected static ?int $navigationSort = 0;
    protected static ?string $navigationLabel = 'Buku Tamu';

    public static function canAccess(): bool
    {
        return Auth::check(); // Semua user yang login boleh akses
    }

    public static function canCreate(): bool
    {
        return Auth::check(); // Atau sesuaikan
    }

    public static function canEdit($record): bool
    {
        return Auth::check();
    }

    public static function canDelete($record): bool
    {
        return Auth::check();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Tamu')
                    ->required()
                    ->maxLength(255),
                Select::make('user_id')
                    ->label('Ketemu Siapa')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->placeholder('Pilih Pegawai'),
                TextInput::make('purpose')
                    ->label('Tujuan')
                    ->required()
                    ->maxLength(255),
                TextInput::make('phoneNumber')
                    ->label('Nomor Handphone')
                    ->required()
                    ->maxLength(15),
                Textarea::make('address')
                    ->label('Alamat')
                    ->required()
                    ->maxLength(255),
                DateTimePicker::make('created_at')
                    ->label('Jam Datang')
                    ->required()
                    ->default(now())
                    ->withoutSeconds()
                    ->native(false),
                DateTimePicker::make('leave')
                    ->label('Jam Keluar')
                    ->default(null)
                    ->native(false),
                SignaturePad::make('signature')
                    ->label(__('Tanda Tangan Disini'))
                    ->dotSize(2.0)
                    ->lineMinWidth(0.5)
                    ->lineMaxWidth(2.5)
                    ->throttle(16)
                    ->minDistance(5)
                    ->velocityFilterWeight(0.7)
                    ->backgroundColor('rgba(255, 255, 255, 0)')  // Background color on light mode
                    ->backgroundColorOnDark('#fffff')     // Background color on dark mode (defaults to backgroundColor)
                    ->exportBackgroundColor('#fffff')     // Background color on export (defaults to backgroundColor)
                    ->penColor('#000')                  // Pen color on light mode
                    ->penColorOnDark('#fff')            // Pen color on dark mode (defaults to penColor)
                    ->exportPenColor('#0f0')            // Pen color on export (defaults to penColor)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Tamu')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Ketemu Siapa')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('purpose')
                    ->label('Tujuan')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Jam Datang')
                    ->dateTime('d/m/Y H:i')
                    ->badge()
                    ->color(fn ($state): string => match ($state) {
                        Carbon::today()->toDateString() => 'success',
                        default => 'primary',
                    }),
                TextColumn::make('leave')
                    ->label('Jam Keluar')
                    ->getStateUsing(fn ($record) => $record->leave ? Carbon::parse($record->leave)->format('d/m/Y H:i') : 'Belum Keluar')
                    ->badge()
                    ->color(fn ($state): string => match ($state) {
                        'Belum Keluar' => 'danger',
                        default => 'danger',
                    }),
                ImageColumn::make('photo')
                    ->label('Foto')
                    ->circular()
                    ->size(50)
                    ->default('https://ui-avatars.com/api/?name=Guest&background=random&color=fff')
                    ->toggleable(),  
            ])
            ->filters([
                //last data
                Filter::make('today')
                    ->label('Hari Ini')
                    ->query(fn (Builder $query) => $query->whereDate('created_at', now()->toDateString())),
                Filter::make('this_week')
                    ->label('Minggu Ini')
                    ->query(fn (Builder $query) => $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])),
                Filter::make('this_month')
                    ->label('Bulan Ini')
                    ->query(fn (Builder $query) => $query->whereMonth('created_at', now()->month)),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->successNotificationTitle('Tamu berhasil dihapus')
                    ->color('danger')
                    ->requiresConfirmation(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
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
            'index' => ListBukuTamus::route('/'),
            'create' => CreateBukuTamu::route('/create'),
            'edit' => EditBukuTamu::route('/{record}/edit'),
        ];
    }
}
