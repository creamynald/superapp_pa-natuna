<?php

namespace Database\Seeders\User;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleUser extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // using spatie
        // make super_admin as role
        $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'super_admin']);
        // make permission
        $permissions = [
            'view_any user',
            'view user',
            'create user',
            'update user',
            'delete user',
            'force_delete user',
            'restore user',
            'restore_any user',
        ];
        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $permission]);
        }
        // assign permissions to role
        $role->givePermissionTo($permissions);
        // assign role to user
        // make sure the user exists
        // if not, it will not assign the role
        // make sure the user exists in the database
        $user = \App\Models\User::where('email', 'creamynald@gmail.com')->first();
        if ($user) {
            $user->assignRole($role);
        } else {    
            \App\Models\User::create([
                'name' => 'Renaldi',
                'email' => 'creamynald@g,ail.com',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
            ])->assignRole($role);  
        }
    }
}
