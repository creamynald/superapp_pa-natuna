<?php

namespace App\Filament\Resources\Kesekretariatan\PermohonanCutis\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Hidden;

class PermohonanCutiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Pemohon')
                ->description('Data Pegawai disi otomatis oleh sistem')
                ->schema([
                    TextInput::make('employee_snapshot.name')
                    ->default(fn()=>auth()->user()->name)->disabled()->dehydrated(true),
                    TextInput::make('employee_snapshot.nip')
                    ->default(fn()=>optional(auth()->user()->pegawai)->nip)->disabled()->dehydrated(true),
                    TextInput::make('employee_snapshot.jabatan')
                    ->default(fn()=>optional(auth()->user()->pegawai)->jabatan)->disabled()->dehydrated(true),
                    TextInput::make('employee_snapshot.gol')
                    ->default(fn()=>optional(auth()->user()->pegawai)->pangkat_golongan)->disabled()->dehydrated(true),
                    TextInput::make('employee_snapshot.masa_kerja')
                        ->default(function () {
                            $pegawai = optional(auth()->user()->pegawai);
                            if (!$pegawai || !$pegawai->tmt_pegawai) {
                                return null;
                            }

                            try {
                                $tmt = \Carbon\Carbon::parse($pegawai->tmt_pegawai)->startOfDay();
                                $now = now()->startOfDay();

                                if ($now < $tmt) {
                                    return '0 tahun 0 bulan';
                                }

                                $diff = $tmt->diff($now);
                                return "{$diff->y} tahun {$diff->m} bulan";
                            } catch (\Exception $e) {
                                return 'TMT tidak valid';
                            }
                        })
                    ->disabled()
                    ->dehydrated(true),
            ])->columns(2),

            Section::make('Data Cuti')
                ->description('Isi data cuti dengan lengkap')
                ->schema([
                    Select::make('leave_type_id')
                    ->label('Jenis Cuti')->searchable()->required()
                    ->options(function(){
                        $user = auth()->user();
                        $year = now()->year;
                        $types = \App\Models\Kesekretariatan\Cuti\TipeCuti::all();
                        $service = app(\App\Services\LeaveQuotaService::class);
                        return $types->filter(function($type) use($user,$year,$service){
                            $q = $service->getYearQuota($user->id,$type->id,$year);
                            if (is_null($q->total_allowed)) return true; // tak terbatas
                            return $q->used_days < $q->total_allowed;
                        })->pluck('name','id');
                    }),

                    DatePicker::make('start_date')->native(false)->required(),
                    DatePicker::make('end_date')->native(false)->required(),
                    Textarea::make('reason')->label('Alasan'),
                    Textarea::make('address_on_leave')->label('Alamat saat Cuti'),
                    TextInput::make('phone_on_leave')->label('Telepon saat Cuti'),
            ])->columns(2),

            Hidden::make('user_id')->default(fn()=>auth()->id()),
            Hidden::make('status')->default('draft'),
        ]);
    }
}
