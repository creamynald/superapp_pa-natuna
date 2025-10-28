<?php

namespace App\Filament\Resources\Settings\ShortcutApps\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;

class ShortcutAppsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Aplikasi'),
                TextColumn::make('url')
                    ->label('URL')
                    ->icon('heroicon-m-globe-alt')
                    ->iconColor('primary')
                    ->url(function ($record) {
                            $u = trim($record->url ?? '');
                            return Str::startsWith($u, ['http://', 'https://'])
                                ? $u
                                : 'https://' . ltrim($u, '/');
                        })
                    ->openUrlInNewTab()
                    ->formatStateUsing(fn ($state) => parse_url($state, PHP_URL_HOST) ?? $state)
                    ->tooltip(fn ($record) => "Kunjungi {$record->url}"),
                TextColumn::make('order')
                    ->label('No Urutan'),
                ImageColumn::make('path')
                    ->label('Icon/Logo Aplikasi')
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
}
