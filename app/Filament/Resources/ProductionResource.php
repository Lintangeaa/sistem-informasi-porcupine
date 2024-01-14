<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductionResource\Pages;
use App\Filament\Resources\ProductionResource\RelationManagers;
use App\Models\Production;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Navigation\NavigationItem;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductionResource extends Resource
{
    protected static ?string $model = Production::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('raw_weight')
                    ->label('Berat Bahan')
                    ->suffix("/Kg")
                    ->placeholder("20")
                    ->numeric()
                    ->required(),
                TextInput::make('produced_weight')
                    ->label('Berat Bahan')
                    ->suffix("/Kg")
                    ->placeholder("20")
                    ->numeric()
                    ->required(),
                Forms\Components\DateTimePicker::make('produced_at')
                    ->label('Diproduksi Pada')
                ->required()
            ]);
    }
    public static function getNavigationItems(): array
    {
        return [
            NavigationItem::make('Produksi')
                ->icon('heroicon-o-scale')
                ->url(ProductionResource::getUrl())
                ->visible(auth()->user()->role == "admin")
                ->isActiveWhen(fn (): bool => request()->url() == ProductionResource::getUrl())
        ];
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('raw_weight')
                    ->label('Berat Bahan')
                    ->formatStateUsing(fn (string $state): string => number_format($state, 0, ',', '.') . "Kg"),
                TextColumn::make('produced_weight')
                    ->label('Berat Hasil Produksi')
                    ->formatStateUsing(fn (string $state): string => number_format($state, 0, ',', '.') . "Kg"),
                TextColumn::make('produced_at')
                    ->formatStateUsing(fn (string $state): string => Date($state))
                    ->label('Diproduksi Pada'),
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
            'index' => Pages\ListProductions::route('/'),
            'create' => Pages\CreateProduction::route('/create'),
            'view' => Pages\ViewProduction::route('/{record}'),
            'edit' => Pages\EditProduction::route('/{record}/edit'),
        ];
    }
}
