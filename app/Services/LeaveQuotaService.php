<?php

namespace App\Services;

use DomainException;
use App\Models\Kesekretariatan\Cuti\{TipeCuti, KuotaCuti, TipeCutiLimit, PermohonanCuti};
use Carbon\Carbon;

class LeaveQuotaService {
    public function getYearQuota(int $userId, int $TipeCutiId, int $year): KuotaCuti {
        return KuotaCuti::firstOrCreate(
            ['user_id'=>$userId,'leave_type_id'=>$TipeCutiId,'year'=>$year],
            ['total_allowed'=>$this->resolveAllowed($userId,$TipeCutiId)]
        );
    }

    protected function resolveAllowed(int $userId, int $TipeCutiId): ?int {
        $override = TipeCutiLimit::where(['user_id'=>$userId,'leave_type_id'=>$TipeCutiId])->first();
        if ($override) return $override->quota_days;
        return TipeCuti::findOrFail($TipeCutiId)->default_quota_days; // null = tak terbatas
    }

    public function ensureWithinQuota(int $userId, int $TipeCutiId, Carbon $start, Carbon $end): void {
        $days = $start->diffInDays($end) + 1;
        $year = (int)$start->year;
        $quota = $this->getYearQuota($userId, $TipeCutiId, $year);

        if (is_null($quota->total_allowed)) return; // tak terbatas

        $used = PermohonanCuti::where('user_id',$userId)
            ->where('leave_type_id',$TipeCutiId)
            ->whereYear('start_date',$year)
            ->where('status','final_approved')
            ->sum('duration_days');

        if (($used + $days) > $quota->total_allowed) {
            throw new DomainException('Kuota jenis cuti ini untuk tahun berjalan sudah habis.');
        }
    }

    public function consumeQuota(PermohonanCuti $req): void {
        $year = (int)$req->start_date->year;
        $quota = $this->getYearQuota($req->user_id, $req->leave_type_id, $year);
        if (!is_null($quota->total_allowed)) {
            $quota->used_days = (int)$quota->used_days + (int)$req->duration_days;
            $quota->save();
        }
    }
}