<?php

namespace App\Filament\Resources\Kepaniteraan\DataSaksiResource\Pages;

use App\Filament\Resources\Kepaniteraan\DataSaksiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDataSaksi extends EditRecord
{
    protected static string $resource = DataSaksiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
