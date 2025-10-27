<?php

namespace App\Filament\Resources\Kesekretariatan\PermohonanCutis\Pages;

use App\Filament\Resources\Kesekretariatan\PermohonanCutis\PermohonanCutiResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPermohonanCuti extends EditRecord
{
    protected static string $resource = PermohonanCutiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
