<?php

namespace App\Filament\Resources\Kesekretariatan\BukuTamuResource\Pages;

use App\Filament\Resources\Kesekretariatan\BukuTamuResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class EditBukuTamu extends EditRecord
{
    protected static string $resource = BukuTamuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            // tombol pulang field leave
            Actions\Action::make('leave')
                ->label('Pulang')
                ->icon('heroicon-o-check')
                ->requiresConfirmation()
                ->action(function () {
                    $record = $this->getRecord();
                    $record->leave = now();
                    $record->save();
                    Notification::make()
                        ->title('Tamu Telah Pulang')
                        ->success()
                        ->send();
                    $this->redirect(BukuTamuResource::getUrl('index'));
                })
        ];
    }
}
