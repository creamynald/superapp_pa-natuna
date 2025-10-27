<?php

namespace App\Filament\Resources\Kesekretariatan\PermohonanCutis;

use App\Filament\Resources\Kesekretariatan\PermohonanCutis\Pages\CreatePermohonanCuti;
use App\Filament\Resources\Kesekretariatan\PermohonanCutis\Pages\EditPermohonanCuti;
use App\Filament\Resources\Kesekretariatan\PermohonanCutis\Pages\ListPermohonanCutis;
use App\Filament\Resources\Kesekretariatan\PermohonanCutis\Schemas\PermohonanCutiForm;
use App\Filament\Resources\Kesekretariatan\PermohonanCutis\Tables\PermohonanCutisTable;
use BackedEnum;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Models\Kesekretariatan\Cuti\PermohonanCuti;

class PermohonanCutiResource extends Resource
{

    protected static string | \UnitEnum | null $navigationGroup = 'Kesekretariatan';
    protected static ?string $navigationLabel = 'Cuti';
    protected static ?string $modelLabel = 'Cuti';

    protected static ?string $model = PermohonanCuti::class;
    
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static ?string $recordTitleAttribute = 'Cuti';

    public static function form(Schema $schema): Schema
    {
        return PermohonanCutiForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PermohonanCutisTable::configure($table);
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
            'index' => ListPermohonanCutis::route('/'),
            'create' => CreatePermohonanCuti::route('/create'),
            'edit' => EditPermohonanCuti::route('/{record}/edit'),
        ];
    }
}
