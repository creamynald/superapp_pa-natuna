<?php

namespace App\Filament\Resources\Kesekretariatan\BukuTamus\Schemas;

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
use Saade\FilamentAutograph\Forms\Components\SignaturePad;

class BukuTamuForm
{
    public static function configure(Schema $schema): Schema
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
}
