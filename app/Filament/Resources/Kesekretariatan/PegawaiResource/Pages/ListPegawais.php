<?php

namespace App\Filament\Resources\Kesekretariatan\PegawaiResource\Pages;

use App\Filament\Resources\Kesekretariatan\PegawaiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPegawais extends ListRecords
{
    protected static string $resource = PegawaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
