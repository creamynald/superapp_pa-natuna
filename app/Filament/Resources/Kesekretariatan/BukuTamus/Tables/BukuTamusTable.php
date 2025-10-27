<?php

namespace App\Filament\Resources\Kesekretariatan\BukuTamus\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Carbon\Carbon;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\Filter;
use Filament\Actions\DeleteAction;

class BukuTamusTable
{
    public static function configure(Table $table): Table
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
            ->defaultSort('created_at', 'desc')
            ->toolbarActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
