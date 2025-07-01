<?php

namespace App\Filament\Resources\Kesekretariatan\BukuTamuResource\Pages;

use App\Filament\Resources\Kesekretariatan\BukuTamuResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBukuTamu extends EditRecord
{
    protected static string $resource = BukuTamuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('pulang')
                ->label('Pulang')
                ->icon('heroicon-o-check')
                ->requiresConfirmation()
                ->action(function ($record) {
                    $record->update(['leave' => now()]);
                })
                ->visible(fn ($record) => is_null($record->leave) && $record->created_at->isToday())
                ->color('success'),
        ];
    }
}
