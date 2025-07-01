<?php

namespace App\Filament\Resources\Kepaniteraan\DataSaksiResource\Pages;

use App\Filament\Resources\Kepaniteraan\DataSaksiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDataSaksis extends ListRecords
{
    protected static string $resource = DataSaksiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
