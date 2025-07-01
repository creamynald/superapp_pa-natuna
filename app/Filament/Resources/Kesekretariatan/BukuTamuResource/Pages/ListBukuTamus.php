<?php

namespace App\Filament\Resources\Kesekretariatan\BukuTamuResource\Pages;

use App\Filament\Resources\Kesekretariatan\BukuTamuResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBukuTamus extends ListRecords
{
    protected static string $resource = BukuTamuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
