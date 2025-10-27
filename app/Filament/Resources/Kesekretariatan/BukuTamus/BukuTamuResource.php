<?php

namespace App\Filament\Resources\Kesekretariatan\BukuTamus;

use App\Filament\Resources\Kesekretariatan\BukuTamus\Pages\CreateBukuTamu;
use App\Filament\Resources\Kesekretariatan\BukuTamus\Pages\EditBukuTamu;
use App\Filament\Resources\Kesekretariatan\BukuTamus\Pages\ListBukuTamus;
use App\Filament\Resources\Kesekretariatan\BukuTamus\Schemas\BukuTamuForm;
use App\Filament\Resources\Kesekretariatan\BukuTamus\Tables\BukuTamusTable;
use App\Models\Kesekretariatan\BukuTamu;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BukuTamuResource extends Resource
{
    protected static ?string $model = BukuTamu::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'BukuTamu';

    public static function form(Schema $schema): Schema
    {
        return BukuTamuForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BukuTamusTable::configure($table);
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
