<?php

namespace App\Filament\Resources\HistorySalesResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SaleRelationManager extends RelationManager
{
    protected static string $relationship = 'sale';
    protected static ?string $title = "Pengajuan Penjualan";
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('weight')
                    ->label('Berat')
                    ->suffix("/Kg")
                    ->placeholder("20")
                    ->reactive()
                    ->numeric()
                    ->afterStateUpdated(fn ($state, callable $set) => $set('total', $state * \App\Models\Price::all()->first()->price) ?? 1)
                    ->required(),
                TextInput::make('price')
                    ->label('Harga Saat Ini')
                    ->default(\App\Models\Price::all()->first()->price ?? 1)
                    ->numeric()
                    ->suffix('/Kg')
                    ->readOnly(),
                TextInput::make('total')
                    ->prefix('Rp. ')
                    ->label('Total Penjualan')
                    ->default(0)
                    ->numeric()
                    ->readOnly(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('weight')
                    ->label('Berat')
                    ->formatStateUsing(fn (string $state): string => number_format($state, 0, ',', '.') . "Kg"),
                TextColumn::make('price')
                    ->label('Harga')
                    ->formatStateUsing(fn (string $state): string => "Rp. " . number_format($state, 0, ',', '.') . " /Kg"),

                TextColumn::make('total')
                    ->formatStateUsing(fn (string $state): string => "Rp. " . number_format($state, 0, ',', '.')),
            ])
            ->filters([
                //
            ])

            ->headerActions([
//                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
//                Tables\Actions\EditAction::make(),
//                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
