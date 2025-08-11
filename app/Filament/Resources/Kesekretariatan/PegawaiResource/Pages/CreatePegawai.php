<?php

namespace App\Filament\Resources\Kesekretariatan\PegawaiResource\Pages;

use App\Filament\Resources\Kesekretariatan\PegawaiResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePegawai extends CreateRecord
{
    protected static string $resource = PegawaiResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = \App\Models\User::create([
            'name'     => $data['nama'],
            'email'    => null,
            'password' => bcrypt($data['nip']),
        ]);

        $user->assignRole('pegawai');
        $data['user_id'] = $user->id;

        return $data;
    }
}
