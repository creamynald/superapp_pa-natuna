<?php

namespace App\Filament\Resources\Kesekretariatan\TipeCutiResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\Kesekretariatan\TipeCutiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTipeCuti extends EditRecord
{
    protected static string $resource = TipeCutiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
