<?php

namespace App\Filament\Resources\Settings\ShortcutApps;

use App\Filament\Resources\Settings\ShortcutApps\Pages\CreateShortcutApp;
use App\Filament\Resources\Settings\ShortcutApps\Pages\EditShortcutApp;
use App\Filament\Resources\Settings\ShortcutApps\Pages\ListShortcutApps;
use App\Filament\Resources\Settings\ShortcutApps\Schemas\ShortcutAppForm;
use App\Filament\Resources\Settings\ShortcutApps\Tables\ShortcutAppsTable;
use App\Models\Settings\ShortcutApp;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ShortcutAppResource extends Resource
{

    protected static string | \UnitEnum | null $navigationGroup = 'Settings';
    protected static ?string $model = ShortcutApp::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-bars-4';

    protected static ?string $recordTitleAttribute = 'ShortcutApp';

    public static function form(Schema $schema): Schema
    {
        return ShortcutAppForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ShortcutAppsTable::configure($table);
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
            'index' => ListShortcutApps::route('/'),
            'create' => CreateShortcutApp::route('/create'),
            'edit' => EditShortcutApp::route('/{record}/edit'),
        ];
    }
}
