<?php

namespace App\Filament\Resources\Kesekretariatan\PermohonanCutis\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;

class PermohonanCutisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('leaveType.name')->label('Jenis'),
                TextColumn::make('start_date')->date('d M Y'),
                TextColumn::make('end_date')->date('d M Y'),
                TextColumn::make('duration_days')->label('Hari'),
                TextColumn::make('status')
                    ->colors(['gray'=>'draft','warning'=>'submitted','info'=>'manager_approved','success'=>'final_approved','danger'=>'*_rejected']),
            ])
            ->actions([
                Action::make('submit')
                ->visible(fn(LeaveRequest $r)=>$r->status==='draft' && $r->user_id===auth()->id())
                ->label('Submit')
                ->requiresConfirmation()
                ->action(function(LeaveRequest $r){
                    // hitung durasi
                    if ($r->end_date->lt($r->start_date)) {
                        throw new \DomainException('Tanggal selesai tidak boleh sebelum tanggal mulai.');
                    }
                    $r->duration_days = $r->start_date->diffInDays($r->end_date)+1;

                    // cek overlap
                    $overlap = LeaveRequest::where('user_id',$r->user_id)
                        ->whereIn('status',['submitted','manager_approved','final_approved'])
                        ->where(function($q) use($r){
                            $q->whereBetween('start_date',[$r->start_date,$r->end_date])
                            ->orWhereBetween('end_date',[$r->start_date,$r->end_date])
                            ->orWhere(function($qq) use($r){
                                $qq->where('start_date','<=',$r->start_date)
                                    ->where('end_date','>=',$r->end_date);
                            });
                        })->exists();
                    if ($overlap) throw new \DomainException('Rentang tanggal bertabrakan dengan pengajuan lain.');

                    // lampiran wajib?
                    if ($r->leaveType->require_attachment && $r->attachments()->count()===0){
                        throw new \DomainException('Jenis cuti ini memerlukan lampiran.');
                    }

                    // kuota
                    app(\App\Services\LeaveQuotaService::class)
                        ->ensureWithinQuota($r->user_id,$r->leave_type_id,$r->start_date,$r->end_date);

                    // snapshot identitas (lock dokumen)
                    $u=$r->user; $e=$u->employee;
                    $r->employee_snapshot = [
                        'name'=>$u->name,
                        'nip'=>optional($e)->nip,
                        'jabatan'=>optional($e)->jabatan,
                        'gol'=>optional($e)->pangkat_golongan,
                        'masa_kerja'=>($e && $e->tmt_pegawai)? \Carbon\Carbon::parse($e->tmt_pegawai)->diffInYears(now()).' tahun' : null,
                    ];

                    $r->status='submitted';
                    $r->save();
                }),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
