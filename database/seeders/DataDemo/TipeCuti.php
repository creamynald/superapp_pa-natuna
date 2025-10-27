<?php

namespace Database\Seeders\DataDemo;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipeCuti extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('leave_types')->insert([
            ['name'=>'Cuti Tahunan','default_quota_days'=>12,'require_attachment'=>false],
            ['name'=>'Cuti Sakit','default_quota_days'=>null,'require_attachment'=>true],
            ['name'=>'Cuti Melahirkan','default_quota_days'=>90,'require_attachment'=>true],
            ['name'=>'Cuti Karena Alasan Penting','default_quota_days'=>null,'require_attachment'=>false],
            ['name'=>'Cuti di Luar Tanggungan Negara','default_quota_days'=>null,'require_attachment'=>false],
        ]);
    }
}
