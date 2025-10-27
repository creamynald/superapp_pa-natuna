<?php

namespace App\Filament\Resources\Settings;
 
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Settings\UserResource\Pages\ListUsers;
use App\Filament\Resources\Settings\UserResource\Pages\CreateUser;
use App\Filament\Resources\Settings\UserResource\Pages\EditUser;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use App\Filament\Resources\Settings\UserResource\Pages;
use App\Filament\Resources\Settings\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-user-group';

    protected static string | \UnitEnum | null $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 999999999999999;

    public static function form(Schema $schema): Schema
    {
        $isSuper = auth()->user()?->hasRole('super_admin');

        return $schema
            ->components([
                // with roles
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Full Name'),
                TextInput::make('email')
                    ->required()
                    ->email()       
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->label('Email Address'),
                Select::make('roles')
                    ->relationship('roles', 'name')
                     ->multiple()   
                    ->preload()
                    ->searchable()
                    ->disabled(!$isSuper),
                TextInput::make('phone_number')
                    ->tel()
                    ->placeholder('08XXXXXXXXXX')
                    ->rule('regex:/^(?:\+62|62|0)8[1-9][0-9]{6,10}$/')
                    ->maxLength(20)
                    ->label('Phone Number')
                    ->required(fn (string $operation): bool => $operation === 'create'),
                TextInput::make('password')
                    ->password()
                    ->revealable()
                    ->label('Password')
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null)
                    ->dehydrated(fn ($state) => filled($state))
                    ->rules(['nullable', 'confirmed']),
                TextInput::make('password_confirmation')
                    ->password()
                    ->revealable()
                    ->label('Confirm Password')
                    ->dehydrated(false)
                    ->required(fn (string $operation): bool => $operation === 'create'), 
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('roles.name')
                    ->label('Role')
                    ->badge(),
                TextColumn::make('created_at')
                    ->dateTime() 
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
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
