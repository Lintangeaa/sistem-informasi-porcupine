<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Navigation\NavigationItem;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;

class UserResource extends Resource
{
    protected static ?string $model = User::class;


    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationItems(): array
    {
        return [
            NavigationItem::make('User')
            ->icon('heroicon-o-user')
            ->url(UserResource::getUrl())
            ->visible(auth()->user()->role == "admin")
            ->isActiveWhen(fn (): bool => request()->url() == UserResource::getUrl())
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('role', 'vendor');
    }

    protected function getFormSchema(): array
    {
        return [
            'name' => ['required'],
            'address' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['nullable'],
            'role' => ['in:vendor,admin']
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('address')
                    ->required(),
                TextInput::make('email')
                    ->required(),
                Select::make('role')->options(['vendor' => 'Vendor', 'admin' => 'Admin'])->default('vendor'),
                TextInput::make('password')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('email'),
                TextColumn::make('address'),
                IconColumn::make('email_verified_at')
                    ->icon(function (string $state): string {
                        if ($state != null) {
                            return 'heroicon-o-check';
                        }
                        return 'heroicon-o-x-mark';
                    })
                    ->color(function (string $state): string {
                        if ($state != null) {
                            return 'success';
                        }
                        return 'danger';
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
