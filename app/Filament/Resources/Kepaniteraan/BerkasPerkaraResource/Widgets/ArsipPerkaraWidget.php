<?php

namespace App\Filament\Resources\Kepaniteraan\BerkasPerkaraResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Kepaniteraan\BerkasPerkara;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ArsipPerkaraWidget extends BaseWidget
{
    protected function getStats(): array
    {
       $tahunIni = Carbon::now()->year;

        // Query dasar (tidak langsung dieksekusi)
        $baseQuery = BerkasPerkara::query()->whereYear('tanggal_masuk', $tahunIni);

        // Jumlah semua arsip perkara (tanpa filter tahun)
        $jumlahSemuaArsip = BerkasPerkara::count();

        // Statistik Arsip Tersedia (cloned query)
        $arsipTersedia = clone $baseQuery;
        $countTersedia = $arsipTersedia->where('status', 'tersedia')->count();

        // Statistik Arsip Dipinjam (cloned query)
        $arsipDipinjam = clone $baseQuery;
        $countDipinjam = $arsipDipinjam->where('status', 'dipinjam')->count(); // pastikan status benar

        return [
            Stat::make('Total Arsip Perkara', $jumlahSemuaArsip)
                ->description('di PA Natuna'),
            Stat::make('Arsip Tersedia', $countTersedia)
                ->description('Tahun ' . $tahunIni)
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            Stat::make('Arsip Dipinjam', $countDipinjam)
                ->description('Tahun ' . $tahunIni)
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->color('warning'),
        ];
    }
}
