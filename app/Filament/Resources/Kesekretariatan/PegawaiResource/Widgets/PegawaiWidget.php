<?php

namespace App\Filament\Resources\Kesekretariatan\PegawaiResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Kesekretariatan\Pegawai;

class PegawaiWidget extends BaseWidget
{
    protected function getStats(): array
    {
        // Total semua pegawai
        $totalPegawai = Pegawai::count();

        // Total hakim (termasuk Ketua & Wakil Ketua)
        $totalHakim = Pegawai::where(function ($q) {
                $q->where('jabatan', 'like', '%Hakim%')
                  ->orWhere('jabatan', 'like', '%Ketua%')
                  ->orWhere('jabatan', 'like', '%Wakil Ketua%');
            })
            ->count();

        // Total pegawai non hakim
        $totalNonHakim = $totalPegawai - $totalHakim;

        return [
            Stat::make('Semua Pegawai', $totalPegawai)
                ->description('Total pegawai yang terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Jumlah Hakim', $totalHakim)
                ->description('Total hakim yang terdaftar')
                ->descriptionIcon('heroicon-m-users'),

            Stat::make('Jumlah Non Hakim', $totalNonHakim)
                ->description('Total pegawai non hakim yang terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('secondary'),
            
        ];
    }
}
