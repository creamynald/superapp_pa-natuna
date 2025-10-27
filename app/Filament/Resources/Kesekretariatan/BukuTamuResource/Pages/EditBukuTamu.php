<?php

namespace App\Filament\Resources\Kesekretariatan\BukuTamuResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
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
            DeleteAction::make(
                'delete')
                ->label('Hapus')
                ->icon('heroicon-o-trash')
                ->requiresConfirmation()
                ->action(function () {
                    $record = $this->getRecord();
                    $record->delete();
                    Notification::make()
                        ->title('Tamu Telah Dihapus')
                        ->success()
                        ->send();
                    $this->redirect(BukuTamuResource::getUrl('index'));
                }
            ),
            Action::make('leave')
                ->label('Pulang')
                ->icon('heroicon-o-arrow-right-start-on-rectangle')
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
