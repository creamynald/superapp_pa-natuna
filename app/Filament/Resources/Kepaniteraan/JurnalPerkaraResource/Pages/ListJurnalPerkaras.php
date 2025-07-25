<?php

namespace App\Filament\Resources\Kepaniteraan\JurnalPerkaraResource\Pages;

// use App\Filament\Imports\Kepaniteraan\JurnalPerkaraImporter;
use App\Imports\JurnalPerkaraImport;
use App\Filament\Resources\Kepaniteraan\JurnalPerkaraResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Kepaniteraan\JurnalPerkaraResource\Widgets\JurnalPerkaraWidget;

use Filament\Resources\Resource;
use HayderHatem\FilamentExcelImport\Actions\Concerns\CanImportExcelRecords;
use App\Models\Kepaniteraan\JurnalPerkara;
use \EightyNine\ExcelImport\ExcelImportAction;

class ListJurnalPerkaras extends ListRecords
{
    protected static string $resource = JurnalPerkaraResource::class;
    protected static ?string $model = JurnalPerkara::class;

    protected function getHeaderActions(): array
    {
        $adaData = JurnalPerkara::count() > 0;

        if ($adaData) {
            return [
                ExcelImportAction::make()
                    ->color("info")
                    ->label('Update Perkara')
                    ->icon('heroicon-o-arrow-path')
                    ->use(JurnalPerkaraImport::class),
            ];
        } else {
            return [
                ExcelImportAction::make()
                    ->color("warning")
                    ->label('Impor Perkara')
                    ->icon('heroicon-o-cloud-arrow-up')
                    ->use(JurnalPerkaraImport::class),
            ];
        }

        // return [
        //     // Actions\CreateAction::make(),
        //     ExcelImportAction::make()
        //         ->color("warning")
        //         ->label('Impor Perkara')
        //         ->icon('heroicon-o-cloud-arrow-up')
        //         ->use(JurnalPerkaraImport::class)
        // ];
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
