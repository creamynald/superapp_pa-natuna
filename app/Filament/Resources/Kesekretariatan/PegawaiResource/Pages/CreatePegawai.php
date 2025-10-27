<?php

namespace App\Filament\Resources\Kesekretariatan\PegawaiResource\Pages;

use App\Models\User;
use App\Filament\Resources\Kesekretariatan\PegawaiResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreatePegawai extends CreateRecord
{
    protected static string $resource = PegawaiResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $nama = data_get($data, 'user.name')
            ?? data_get($data, 'nama')
            ?? data_get($data, 'name');

        $nip = $data['nip'] ?? null;

        if (! $nama) {
            throw ValidationException::withMessages(['user.name' => 'Nama pegawai wajib diisi.']);
        }
        if (! $nip) {
            throw ValidationException::withMessages(['nip' => 'NIP wajib diisi.']);
        }

        return DB::transaction(function () use ($data, $nama, $nip) {
            $user = User::create([
                'name'     => $nama,
                'email'    => null,        // pastikan kolom email nullable
                'password' => bcrypt($nip),
            ]);

            // Pastikan role ada (guard 'web')
            $role = Role::firstOrCreate(['name' => 'pegawai', 'guard_name' => 'web']);
            $user->assignRole($role);

            $data['user_id'] = $user->id;
            unset($data['user']);

            return $data;
        });
    }
}
