<?php

namespace App\Filament\Resources\Kesekretariatan\PermohonanCutis\Pages;

use App\Filament\Resources\Kesekretariatan\PermohonanCutis\PermohonanCutiResource;
use Filament\Resources\Pages\CreateRecord;
use Carbon\Carbon;

class CreatePermohonanCuti extends CreateRecord
{
    protected static string $resource = PermohonanCutiResource::class;

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        if (!empty($data['start_date']) && !empty($data['end_date'])) {
            $start = Carbon::parse($data['start_date']);
            $end = Carbon::parse($data['end_date']);
            $data['duration_days'] = $start->diffInDays($end) + 1;
        } else {
            $data['duration_days'] = 0;
        }

        return static::getModel()::create($data);
    }
}
