<?php

namespace Database\Seeders\DataDemo;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataBerkas extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('berkas_perkaras')->insert([
            [
                'nomor_perkara' => '123/Pdt.G/2023/PA.Nt',
                'penggugat' => 'John Doe',
                'tergugat' => 'Jane Smith',
                'tanggal_masuk' => now(),
                'status' => 'tersedia',
                'lokasi' => 'Ruang Arsip 1',
                'created_by' => 2,
                'updated_by' => 1,
            ],
            [
                'nomor_perkara' => '456/Pdt.G/2023/PA.Nt',
                'penggugat' => 'Alice Johnson',
                'tergugat' => 'Bob Brown',
                'tanggal_masuk' => now(),
                'status' => 'dipinjam',
                'lokasi' => 'Ruang Arsip 2',
                'created_by' => 3,
                'updated_by' => 2,
            ],
        ]);
    }
}
