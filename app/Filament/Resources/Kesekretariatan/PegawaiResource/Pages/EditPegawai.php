<?php

namespace App\Filament\Resources\Kesekretariatan\PegawaiResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\Kesekretariatan\PegawaiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPegawai extends EditRecord
{
    protected static string $resource = PegawaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $pegawai = $this->record;
        $user    = $pegawai->user;

        if (isset($data['nip']) && $data['nip'] !== $pegawai->nip) {
            $user->password = bcrypt($data['nip']);
            $user->save();
        }

        if (isset($data['nama']) && $data['nama'] !== $pegawai->nama) {
            $user->name = $data['nama'];
            $user->save();
        }

        return $data;
    }
}
