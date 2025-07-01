<?php

namespace App\Filament\Resources\Kesekretariatan\BukuTamuResource\Pages;

use App\Filament\Resources\Kesekretariatan\BukuTamuResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBukuTamu extends EditRecord
{
    protected static string $resource = BukuTamuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
