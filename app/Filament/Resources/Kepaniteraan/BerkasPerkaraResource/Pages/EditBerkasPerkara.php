<?php

namespace App\Filament\Resources\Kepaniteraan\BerkasPerkaraResource\Pages;

use App\Filament\Resources\Kepaniteraan\BerkasPerkaraResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditBerkasPerkara extends EditRecord
{
    protected static string $resource = BerkasPerkaraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
{
    // Akses record via $this->record
    $record = $this->getRecord(); 

    $parts = explode('/', $record->nomor_perkara);
    if (count($parts) >= 4) {
        $data['nomor_urut'] = $parts[0];
        $data['jenis_perkara'] = $parts[1];
        $data['tahun_perkara'] = $parts[2];
    }

    return $data;
}
}