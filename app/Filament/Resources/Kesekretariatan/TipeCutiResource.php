<?php

namespace App\Filament\Resources\Kesekretariatan;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use App\Models\Kesekretariatan\Cuti\PermohonanCuti;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Kesekretariatan\TipeCutiResource\Pages\ListTipeCutis;
use App\Filament\Resources\Kesekretariatan\TipeCutiResource\Pages\CreateTipeCuti;
use App\Filament\Resources\Kesekretariatan\TipeCutiResource\Pages\EditTipeCuti;
use App\Filament\Resources\Kesekretariatan\TipeCutiResource\Pages;
use App\Filament\Resources\Kesekretariatan\TipeCutiResource\RelationManagers;
use App\Models\Kesekretariatan\Cuti\TipeCuti;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Support\Icons\Heroicon;

class TipeCutiResource extends Resource
{
    protected static ?string $model = TipeCuti::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-settings';

    protected static string | \UnitEnum | null $navigationGroup = 'Kesekretariatan';
    protected static ?string $navigationLabel = 'Jenis Cuti';
    protected static ?string $modelLabel = 'Jenis Cuti';
    protected static ?string $pluralModelLabel = 'Jenis Cuti';


    protected static ?string $navigationParentItem = 'Cuti';
    protected static ?string $label = 'Cuti';
    protected static ?string $pluralLabel = 'Cuti';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->required(),
                TextInput::make('default_quota_days')
                            ->numeric()->minValue(0)
                            ->label('Kuota/Tahun')
                            ->helperText('Biarkan kosong untuk tak terbatas'),
                Toggle::make('require_attachment')
                    ->label('Wajib Lampiran?'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('name')
                ->label('Jenis Cuti')
                ->searchable(),

            TextColumn::make('default_quota_days')
                ->label('Kuota/Tahun')
                ->formatStateUsing(fn ($state) => is_null($state) ? 'Tak terbatas' : $state.' hari'),

            TextColumn::make('remaining_quota_for_current_user')
                ->label('Sisa Cuti (tahun ini)')
                ->state(function (TipeCuti $record) {
                    $allowed = $record->default_quota_days;
                    if (is_null($allowed)) {
                        return null; // akan diformat jadi "Tak terbatas"
                    }

                    $used = PermohonanCuti::query()
                        ->where('user_id', auth()->id())
                        ->where('leave_type_id', $record->id)
                        ->whereYear('start_date', now()->year)
                        ->where('status', 'final_approved')
                        ->sum('duration_days');

                    return max(0, (int)$allowed - (int)$used);
                })
                ->formatStateUsing(fn ($state) => is_null($state) ? 'Tak terbatas' : $state.' hari')
                ->sortable(),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTipeCutis::route('/'),
            'create' => CreateTipeCuti::route('/create'),
            'edit' => EditTipeCuti::route('/{record}/edit'),
        ];
    }
}
