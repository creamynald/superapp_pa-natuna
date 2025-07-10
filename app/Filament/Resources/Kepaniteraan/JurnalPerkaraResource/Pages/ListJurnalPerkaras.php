<?php

namespace App\Filament\Resources\Kepaniteraan\JurnalPerkaraResource\Pages;

use App\Filament\Imports\Kepaniteraan\JurnalPerkaraImporter;
use App\Filament\Resources\Kepaniteraan\JurnalPerkaraResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Kepaniteraan\JurnalPerkaraResource\Widgets\JurnalPerkaraWidget;

use Filament\Resources\Resource;
use HayderHatem\FilamentExcelImport\Actions\Concerns\CanImportExcelRecords;
use App\Models\Kepaniteraan\JurnalPerkara;

class ListJurnalPerkaras extends ListRecords
{
    protected static string $resource = JurnalPerkaraResource::class;
    protected static ?string $model = JurnalPerkara::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
            \EightyNine\ExcelImport\ExcelImportAction::make()
                ->color("primary")
                ->label('Impor Perkara')
                ->icon('heroicon-o-cloud-arrow-up'),
                
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            JurnalPerkaraWidget::class,
        ];
    }

    // protected function getFooterWidgets(): array
    // {
    //     return [
    //         JurnalPerkaraWidget::class,
    //     ];
    // }
}
