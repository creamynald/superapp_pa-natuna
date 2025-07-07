<?php

namespace Database\Seeders\User;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DataUser extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Renaldi',
                'email' => 'creamynald@gmail.com',
                'password' => Hash::make('password'),
                'created_at' => now(),
            ],
            [
                'name' => 'Bambang',
                'email' => 'bambang@gmail.com',
                'password' => Hash::make('password'),
                'created_at' => now(),
            ],
            [
                'name' => 'usman',
                'email' => 'usman@gmail.com',
                'password' => Hash::make('password'),
                'created_at' => now(),
            ],
        ]);
    }
}
