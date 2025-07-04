<?php

namespace App\Filament\Resources\Kepaniteraan\PeminjamanBerkasPerkaraResource\Pages;

use App\Filament\Resources\Kepaniteraan\PeminjamanBerkasPerkaraResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPeminjamanBerkasPerkaras extends ListRecords
{
    protected static string $resource = PeminjamanBerkasPerkaraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
