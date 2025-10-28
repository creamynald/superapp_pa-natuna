<?php

namespace App\Filament\Resources\Settings\ShortcutApps\Pages;

use App\Filament\Resources\Settings\ShortcutApps\ShortcutAppResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListShortcutApps extends ListRecords
{
    protected static string $resource = ShortcutAppResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
