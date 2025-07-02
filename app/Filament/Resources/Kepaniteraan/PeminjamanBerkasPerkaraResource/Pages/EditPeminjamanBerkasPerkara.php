<?php

namespace App\Filament\Resources\Kepaniteraan\PeminjamanBerkasPerkaraResource\Pages;

use App\Filament\Resources\Kepaniteraan\PeminjamanBerkasPerkaraResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPeminjamanBerkasPerkara extends EditRecord
{
    protected static string $resource = PeminjamanBerkasPerkaraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
