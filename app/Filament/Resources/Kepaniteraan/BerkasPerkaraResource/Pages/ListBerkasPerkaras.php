<?php

namespace App\Filament\Resources\Kepaniteraan\BerkasPerkaraResource\Pages;

use App\Filament\Resources\Kepaniteraan\BerkasPerkaraResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBerkasPerkaras extends ListRecords
{
    protected static string $resource = BerkasPerkaraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
