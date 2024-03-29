<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PriceResource\Pages;
use App\Filament\Resources\PriceResource\RelationManagers;
use App\Models\Price;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Navigation\NavigationItem;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;

class PriceResource extends Resource
{
    protected static ?string $model = Price::class;
    public static function getNavigationItems(): array
    {
        return [
            NavigationItem::make('Harga')
                ->icon('heroicon-o-currency-dollar')
                ->url(PriceResource::getUrl())
                ->visible(auth()->user()->role == "admin")
                ->activeIcon('heroicon-o-currency-dollar')
                ->isActiveWhen(fn (): bool => request()->url() == PriceResource::getUrl())
        ];
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('price')
                    ->label('Price')
                    ->placeholder('Price')
                    ->prefix("Rp. ")
                    ->suffix('/Kg')
                    ->numeric()
                    ->required()
                    ->maxLength(20),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('price')->formatStateUsing(fn (string $state): string => "Rp. " . number_format($state, 0, ',', '.') . "/Kg"),
                TextColumn::make('updated_at')->formatStateUsing(fn (string $state): string => Date($state))->label('Latest Updated At'),
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
            'index' => Pages\ListPrices::route('/'),
            'create' => Pages\CreatePrice::route('/create'),
            'view' => Pages\ViewPrice::route('/{record}'),
            'edit' => Pages\EditPrice::route('/{record}/edit'),
        ];
    }
}
