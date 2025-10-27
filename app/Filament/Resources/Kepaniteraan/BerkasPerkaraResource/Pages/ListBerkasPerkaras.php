<?php

namespace App\Filament\Resources\Kepaniteraan\BerkasPerkaraResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Kepaniteraan\BerkasPerkaraResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Kepaniteraan\BerkasPerkaraResource\Widgets\ArsipPerkaraWidget;

class ListBerkasPerkaras extends ListRecords
{
    protected static string $resource = BerkasPerkaraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->icon('heroicon-o-plus-circle')
                ->color('primary')
                ->label('Tambah Arsip Perkara')
                ->modalHeading('Tambah Arsip Perkara'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ArsipPerkaraWidget::class,
        ];
    }
}
