<?php

namespace App\Filament\Resources\Settings\ShortcutApps\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Support\Enums\Width;

class ShortcutAppForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('ShortCut Aplikasi')
                    ->description('isi detail berikut untuk menambahkan shortcut aplikasi')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Aplikasi'),
                        TextInput::make('url')
                            ->label('URL Aplikasi'),
                        FileUpload::make('path')
                            ->label('Logo atau Icon Aplikasi')
                            ->directory('image/iconapp')
                            ->visibility('public')
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '1:1',
                            ]),
                        TextInput::make('order')
                            ->label('No Urutan')
                    ])
            ]);
    }
}
