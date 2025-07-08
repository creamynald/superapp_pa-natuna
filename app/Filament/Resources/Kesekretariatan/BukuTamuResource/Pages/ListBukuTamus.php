<?php

namespace App\Filament\Resources\Kesekretariatan\BukuTamuResource\Pages;

use App\Filament\Resources\Kesekretariatan\BukuTamuResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Kesekretariatan\BukuTamuResource\Actions\CreateAction;

class ListBukuTamus extends ListRecords
{
    protected static string $resource = BukuTamuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(
            )->label('Tambah Tamu Baru') // Ganti label
                ->icon('heroicon-o-plus-circle') // Ganti ikon
                ->color('success') // Warna tombol (primary, success, danger, warning)
                ->url(fn (): string => $this->getResource()::getUrl('create')) // opsional
                ->visible(fn (): bool => auth()->user()->can('create_buku_tamu')
            ),    

        ];
    }
}
