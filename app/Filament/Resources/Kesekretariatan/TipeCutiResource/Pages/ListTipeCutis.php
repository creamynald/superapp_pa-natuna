<?php

namespace App\Filament\Resources\Kesekretariatan\TipeCutiResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Kesekretariatan\TipeCutiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTipeCutis extends ListRecords
{
    protected static string $resource = TipeCutiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
