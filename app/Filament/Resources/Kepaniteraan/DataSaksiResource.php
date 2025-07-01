<?php

namespace App\Filament\Resources\Kepaniteraan;

use App\Filament\Resources\Kepaniteraan\DataSaksiResource\Pages;
use App\Filament\Resources\Kepaniteraan\DataSaksiResource\RelationManagers;
use App\Models\Kepaniteraan\DataSaksi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DataSaksiResource extends Resource
{
    protected static ?string $model = DataSaksi::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Kepaniteraan';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationLabel = 'Data Saksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListDataSaksis::route('/'),
            'create' => Pages\CreateDataSaksi::route('/create'),
            'edit' => Pages\EditDataSaksi::route('/{record}/edit'),
        ];
    }
}
