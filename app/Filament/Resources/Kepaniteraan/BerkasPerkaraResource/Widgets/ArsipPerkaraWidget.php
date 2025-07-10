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
        // get tahun berjalan
        $tahunIni = Carbon::now()->year;

        // query dasar untuk arsip perkara
        $baseQuery = BerkasPerkara::query()
            ->whereYear('tanggal_masuk', $tahunIni);
            
        // jumlah semua arsip perkara
        $jumlahSemuaArsip = BerkasPerkara::count();

        return [
            // total arsip perkara
            Stat::make('Total Arsip Perkara', $jumlahSemuaArsip)
                ->description('di PA Natuna'),
            Stat::make('Arsip Tersedia', $baseQuery->where('status', 'tersedia')->count())
                ->description('Tahun ' . $tahunIni)
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            Stat::make('Arsip Dipinjam', $baseQuery->whereHas('peminjaman', function ($query) {
                $query->where('status', 'Dipinjam');
            })->count())
                ->description('Tahun ' . $tahunIni)
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->color('warning'),
        ];
    }
}
