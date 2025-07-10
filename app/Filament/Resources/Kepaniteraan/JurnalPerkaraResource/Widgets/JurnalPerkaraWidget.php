<?php

namespace App\Filament\Resources\Kepaniteraan\JurnalPerkaraResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Kepaniteraan\JurnalPerkara;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JurnalPerkaraWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $tahunIni = Carbon::now()->year;

        // Query dasar dengan ekstraksi tahun dari nomor_perkara
        $baseQuery = JurnalPerkara::query()
            ->whereRaw("SUBSTRING_INDEX(SUBSTRING_INDEX(nomor_perkara, '/', 3), '/', -1) = ?", [$tahunIni]);

        // Jika ingin mengambil semua data tanpa filter tahun
        $jumlahSemuaPerkara = JurnalPerkara::query()
            ->whereRaw("SUBSTRING_INDEX(SUBSTRING_INDEX(nomor_perkara, '/', 3), '/', -1) IS NOT NULL");

        return [
            Stat::make("Jumlah Perkara Tahun {$tahunIni}", $baseQuery->count())
                ->description("Dari {$jumlahSemuaPerkara->count()} perkara")
                ->descriptionIcon('heroicon-m-calendar')
                ->color('primary'),

            // Cerai Gugat
            Stat::make('Cerai Gugat', $baseQuery->clone()->where('klasifikasi_perkara', 'Cerai Gugat')->count())
                ->description('Tahun ini')
                ->descriptionIcon('heroicon-m-user')
                ->chart($this->getDataByMonth($baseQuery->clone()->where('klasifikasi_perkara', 'Cerai Gugat')))
                ->color('warning'),

            // Cerai Talak
            Stat::make('Cerai Talak', $baseQuery->clone()->where('klasifikasi_perkara', 'Cerai Talak')->count())
                ->description('Tahun ini')
                ->descriptionIcon('heroicon-m-x-circle')
                ->chart($this->getDataByMonth($baseQuery->clone()->where('klasifikasi_perkara', 'Cerai Talak')))
                ->color('danger'),

            // Penguasaan Anak
            Stat::make('Penguasaan Anak', $baseQuery->clone()->where('klasifikasi_perkara', 'Penguasaan Anak')->count())
                ->description('Tahun ini')
                ->descriptionIcon('heroicon-o-user')
                ->chart($this->getDataByMonth($baseQuery->clone()->where('klasifikasi_perkara', 'Penguasaan Anak')))
                ->color('info'),

            // Pengesahan Perkawinan
            Stat::make('Pengesahan Perkawinan', $baseQuery->clone()->where('klasifikasi_perkara', 'Pengesahan Perkawinan/Istbat Nikah')->count())
                ->description('Tahun ini')
                ->descriptionIcon('heroicon-m-heart')
                ->chart($this->getDataByMonth($baseQuery->clone()->where('klasifikasi_perkara', 'Pengesahan Perkawinan/Istbat Nikah')))
                ->color('success'),

            // Pembatalan Perkawinan
            Stat::make('Pembatalan Perkawinan', $baseQuery->clone()->where('klasifikasi_perkara', 'Pembatalan Perkawinan')->count())
                ->description('Tahun ini')
                ->descriptionIcon('heroicon-m-minus-circle')
                ->chart($this->getDataByMonth($baseQuery->clone()->where('klasifikasi_perkara', 'Pembatalan Perkawinan')))
                ->color('secondary'),

            // Kewarisan
            Stat::make('Kewarisan', $baseQuery->clone()->where('klasifikasi_perkara', 'Kewarisan')->count())
                ->description('Tahun ini')
                ->descriptionIcon('heroicon-m-gift')
                ->chart($this->getDataByMonth($baseQuery->clone()->where('klasifikasi_perkara', 'Kewarisan')))
                ->color('primary'),
        ];
    }

    // Helper untuk chart per bulan
    private function getDataByMonth($query)
    {
        return $query
            ->selectRaw('MONTH(created_at) as month, count(*) as count')
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('count')
            ->toArray();
    }
}
