<?php

namespace App\Filament\Resources\Kepaniteraan;

use App\Filament\Resources\Kepaniteraan\JurnalPerkaraResource\Pages;
use App\Filament\Resources\Kepaniteraan\JurnalPerkaraResource\RelationManagers;
use App\Models\Kepaniteraan\JurnalPerkara;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\Kepaniteraan\JurnalPerkaraResource\Widgets\JurnalPerkaraWidget;

class JurnalPerkaraResource extends Resource
{
    protected static ?string $model = JurnalPerkara::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Kepaniteraan';
    protected static ?int $navigationSort = 1;
    protected static ?string $label = 'Jurnal Perkara';
    protected static ?string $pluralLabel = 'Jurnal Perkara';
    
    public static function getWidgets(): array
    {
        return [
            JurnalPerkaraWidget::class,
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nomor_perkara')
                    ->label('Nomor Perkara')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('klasifikasi_perkara')
                    ->label('Klasifikasi Perkara')
                    ->options([
                        'Cerai Gugat' => 'Cerai Gugat',
                        'Cerai Talak' => 'Cerai Talak',
                        'Penguasaan Anak' => 'Penguasaan Anak',
                        'Pengesahan Perkawinan/Istbat Nikah' => 'Pengesahan Perkawinan/Istbat Nikah',
                        'Pembatalan Perkawinan' => 'Pembatalan Perkawinan',
                        'Kewarisan' => 'Kewarisan',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('penggugat')
                    ->label('Penggugat')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('tergugat')
                    ->label('Tergugat')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('proses_terakhir')
                    ->label('Proses Terakhir')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_perkara')
                    ->label('Nomor Perkara')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('klasifikasi_perkara')
                    ->label('Klasifikasi Perkara')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'Cerai Gugat' => 'primary',
                        'Cerai Talak' => 'danger',
                        'Penguasaan Anak' => 'success',
                        'Pengesahan Perkawinan/Istbat Nikah' => 'success',
                        'Pembatalan Perkawinan' => 'success',
                        'Kewarisan' => 'success',
                    }),
                Tables\Columns\TextColumn::make('penggugat')
                    ->label('Penggugat')
                    ->limit(25),
                Tables\Columns\TextColumn::make('tergugat')
                    ->label('Tergugat'),
                Tables\Columns\TextColumn::make('proses_terakhir')
                    ->label('Proses Terakhir'),
            ])
            // ->modifyQueryUsing(fn ($query) => $query->latestPerkara())
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListJurnalPerkaras::route('/'),
            // 'create' => Pages\CreateJurnalPerkara::route('/create'),
            'edit' => Pages\EditJurnalPerkara::route('/{record}/edit'),
        ];
    }
}
