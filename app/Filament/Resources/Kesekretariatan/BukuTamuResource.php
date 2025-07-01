<?php

namespace App\Filament\Resources\Kesekretariatan;

use App\Filament\Resources\Kesekretariatan\BukuTamuResource\Pages;
use App\Filament\Resources\Kesekretariatan\BukuTamuResource\RelationManagers;
use App\Models\Kesekretariatan\BukuTamu;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Saade\FilamentAutograph\Forms\Components\SignaturePad;
use Carbon\Carbon;

class BukuTamuResource extends Resource
{
    protected static ?string $model = BukuTamu::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'Umum';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Buku Tamu';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Tamu')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('user_id')
                    ->label('Ketemu Siapa')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->placeholder('Pilih Pegawai'),
                Forms\Components\TextInput::make('purpose')
                    ->label('Tujuan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phoneNumber')
                    ->label('Nomor Handphone')
                    ->required()
                    ->maxLength(15),
                Forms\Components\TextInput::make('address')
                    ->label('Alamat')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('created_at')
                    ->label('Jam Datang')
                    ->required()
                    ->default(now())
                    ->withoutSeconds(),
                Forms\Components\DateTimePicker::make('leave')
                    ->label('Jam Keluar')
                    ->default(null),
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
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Tamu')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Ketemu Siapa')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('purpose')
                    ->label('Tujuan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Jam Datang')
                    ->dateTime('d/m/Y H:i'),
                Tables\Columns\TextColumn::make('leave')
                    ->label('Jam Keluar')
                    ->getStateUsing(fn ($record) => $record->leave ? \Carbon\Carbon::parse($record->leave)->format('d/m/Y H:i') : 'Belum Keluar')
                    ->badge()
                    ->color(fn ($state): string => match ($state) {
                        'Belum Keluar' => 'danger',
                        default => 'success',
                    }),
                Tables\Columns\ImageColumn::make('photo')
                    ->label('Foto')
                    ->circular()
                    ->size(50)
                    ->default('https://ui-avatars.com/api/?name=Guest&background=random&color=fff')
                    ->toggleable(),  
            ])
            ->filters([
                //last data
                Tables\Filters\Filter::make('today')
                    ->label('Hari Ini')
                    ->query(fn (Builder $query) => $query->whereDate('created_at', now()->toDateString())),
                Tables\Filters\Filter::make('this_week')
                    ->label('Minggu Ini')
                    ->query(fn (Builder $query) => $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])),
                Tables\Filters\Filter::make('this_month')
                    ->label('Bulan Ini')
                    ->query(fn (Builder $query) => $query->whereMonth('created_at', now()->month)),
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
            'index' => Pages\ListBukuTamus::route('/'),
            'create' => Pages\CreateBukuTamu::route('/create'),
            'edit' => Pages\EditBukuTamu::route('/{record}/edit'),
        ];
    }
}
