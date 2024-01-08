<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HistorySalesResource\Pages;
use App\Filament\Resources\HistorySalesResource\RelationManagers;
use App\Models\HistorySales;
use Cassandra\Date;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HistorySalesResource extends Resource
{
    protected static ?string $model = HistorySales::class;
    protected static ?string $navigationLabel = "History Penjualan";

    protected static ?string $navigationIcon = 'heroicon-m-banknotes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('accepted_weight')
                    ->label('Berat Diterima/Tolak')
                    ->suffix("/Kg")
                    ->placeholder("20")
                    ->reactive()
                    ->afterStateUpdated(fn ($state, callable $set) => $set('accepted_total', $state * \App\Models\Price::all()->first()->price) ?? 1)
                    ->numeric(),
                TextInput::make('accepted_price')
                    ->label('Harga Saat Ini')
                    ->numeric()
                    ->suffix('/Kg')
                    ->readOnly(),
                TextInput::make('accepted_total')
                    ->prefix('Rp. ')
                    ->label('Total Penjualan')
                    ->numeric()
                    ->readOnly(),
                Forms\Components\Select::make('status')->options([
                    'accepted' => 'Diterima',
                    'decline' => 'Ditolak',
                ]),
                Forms\Components\DateTimePicker::make('created_at')->label('Diterima/Ditolak Pada')
            ]);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('accepted_weight')
                    ->label('Berat')
                    ->formatStateUsing(fn (string $state): string => number_format($state, 0, ',', '.') . "Kg"),
                TextColumn::make('accepted_price')
                    ->label('Harga')
                    ->formatStateUsing(fn (string $state): string => "Rp. " . number_format($state, 0, ',', '.') . " /Kg"),
                TextColumn::make('accepted_total')
                    ->formatStateUsing(fn (string $state): string => "Rp. " . number_format($state, 0, ',', '.')),
                TextColumn::make('created_at')
                    ->formatStateUsing(fn (string $state): string => Date($state))
                    ->label('Diterima/Ditolak Pada'),
                TextColumn::make('status')
                    ->label('Status')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()->hidden(auth()->user()->role == "vendor"),
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
            RelationManagers\SaleRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHistorySales::route('/'),
            'create' => Pages\CreateHistorySales::route('/create'),
            'view' => Pages\ViewHistorySales::route('/{record}'),
            'edit' => Pages\EditHistorySales::route('/{record}/edit'),
        ];
    }
}
