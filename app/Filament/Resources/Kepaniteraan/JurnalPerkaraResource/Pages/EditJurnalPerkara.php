<?php

namespace App\Filament\Resources\Kepaniteraan\JurnalPerkaraResource\Pages;

use App\Filament\Resources\Kepaniteraan\JurnalPerkaraResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJurnalPerkara extends EditRecord
{
    protected static string $resource = JurnalPerkaraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
