<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\User\DataUser;
use Database\Seeders\User\RoleUser;
use Database\Seeders\DataDemo\DataBerkas;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            DataUser::class,
            DataBerkas::class,
            RoleUser::class
        ]);
    }
}
