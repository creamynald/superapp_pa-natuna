<?php

namespace App\Filament\Resources\Kepaniteraan\JurnalPerkaraResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Kepaniteraan\JurnalPerkara;

class JurnalPerkaraWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            
                // count of Jurnal Perkara
                Stat::make('Total Jurnal Perkara', JurnalPerkara::count())
                    ->description('Total Jurnal Perkara')
                    ->descriptionIcon('heroicon-m-document-text')
                    ->chart(JurnalPerkara::selectRaw('count(*) as count')
                        ->groupBy('created_at')
                        ->pluck('count')
                        ->toArray()
                    )
                    ->color('primary'),
                // cerai gugat
                Stat::make('Cerai Gugat', JurnalPerkara::where('klasifikasi_perkara', 'Cerai Gugat')->count())
                    ->description('Total Cerai Gugat')
                    ->descriptionIcon('heroicon-m-document-text')
                    ->chart(JurnalPerkara::where('klasifikasi_perkara', 'Cerai Gugat')
                        ->selectRaw('count(*) as count')
                        ->groupBy('created_at')
                        ->pluck('count')    
                        ->toArray()
                    )
                    ->color('warning'),
                // cerai talak
                Stat::make('Cerai Talak', JurnalPerkara::where('klasifikasi_perkara', 'Cerai Talak')->count())
                    ->description('Total Cerai Talak')
                    ->descriptionIcon('heroicon-m-document-text')
                    ->chart(JurnalPerkara::where('klasifikasi_perkara', 'Cerai Talak')
                        ->selectRaw('count(*) as count')    
                        ->groupBy('created_at')
                        ->pluck('count')
                        ->toArray()
                    )
                    ->color('danger'),
                // penguasaan anak
                Stat::make('Penguasaan Anak', JurnalPerkara::where('klasifikasi_perkara', 'Penguasaan Anak')->count())
                    ->description('Total Penguasaan Anak')
                    ->descriptionIcon('heroicon-m-document-text')
                    ->chart(JurnalPerkara::where('klasifikasi_perkara', 'Penguasaan Anak')
                        ->selectRaw('count(*) as count')    
                        ->groupBy('created_at')
                        ->pluck('count')
                        ->toArray()
                    )
                    ->color('info'),
                // pengesahan perkawinan
                Stat::make('Pengesahan Perkawinan', JurnalPerkara::where('klasifikasi_perkara', 'Pengesahan Perkawinan/Istbat Nikah')->count())
                    ->description('Total Pengesahan Perkawinan')
                    ->descriptionIcon('heroicon-m-document-text')
                    ->chart(JurnalPerkara::where('klasifikasi_perkara', 'Pengesahan Perkawinan/Istbat Nikah')
                        ->selectRaw('count(*) as count ')
                        ->groupBy('created_at')
                        ->pluck('count')
                        ->toArray()
                    )
                    ->color('success'),
                // pembatalan perkawinan
                Stat::make('Pembatalan Perkawinan', JurnalPerkara::where('klasifikasi_perkara', 'Pembatalan Perkawinan')->count())
                    ->description('Total Pembatalan Perkawinan')
                    ->descriptionIcon('heroicon-m-document-text')
                    ->chart(JurnalPerkara::where('klasifikasi_perkara', 'Pembatalan Perkawinan')
                        ->selectRaw('count(*) as count')
                        ->groupBy('created_at')
                        ->pluck('count')
                        ->toArray()
                    )
                    ->color('secondary'),
                // kewarisan
                Stat::make('Kewarisan', JurnalPerkara::where('klasifikasi_perkara', 'Kewarisan')->count())
                    ->description('Total Kewarisan')
                    ->descriptionIcon('heroicon-m-document-text')
                    ->chart(JurnalPerkara::where('klasifikasi_perkara', 'Kewarisan')
                        ->selectRaw('count(*) as count')
                        ->groupBy('created_at')
                        ->pluck('count')        
                        ->toArray()
                    )
                    ->color('primary'), 
        ];
    }
}
