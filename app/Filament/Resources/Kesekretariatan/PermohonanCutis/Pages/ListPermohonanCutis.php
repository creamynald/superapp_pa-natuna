<?php

namespace App\Filament\Resources\Kesekretariatan\PermohonanCutis\Pages;

use App\Filament\Resources\Kesekretariatan\PermohonanCutis\PermohonanCutiResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;

class ListPermohonanCutis extends ListRecords
{
    protected static string $resource = PermohonanCutiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
