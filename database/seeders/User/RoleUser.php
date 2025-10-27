<?php

namespace Database\Seeders\User;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Models\Role;

class RoleUser extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Bersihkan cache permission/role
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Pastikan role super_admin ada
        $role = Role::firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => 'web',
        ]);

        // Ambil user creamynald; kalau belum ada, buat sekalian
        $user = User::firstOrCreate(
            ['email' => 'creamynald@gmail.com'],
            ['name' => 'Renaldi', 'password' => Hash::make('password')]
        );

        // Assign role (idempotent)
        $user->syncRoles([$role]); // atau $user->assignRole($role);
    }
}
