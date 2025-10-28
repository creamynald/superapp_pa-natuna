<?php

namespace App\Filament\Resources\Settings\ShortcutApps\Pages;

use App\Filament\Resources\Settings\ShortcutApps\ShortcutAppResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditShortcutApp extends EditRecord
{
    protected static string $resource = ShortcutAppResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
