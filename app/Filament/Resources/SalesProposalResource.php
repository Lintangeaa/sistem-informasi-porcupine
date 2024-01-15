<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SalesProposalResource\Pages;
use App\Filament\Resources\SalesProposalResource\RelationManagers;
use App\Models\HistorySales;
use App\Models\SalesProposal;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Pages\Actions;

class SalesProposalResource extends Resource
{

    protected static ?string $model = SalesProposal::class;
    protected static ?string $navigationLabel = "Pengajuan Penjualan";

    protected static ?string $navigationIcon = 'heroicon-s-document-chart-bar';

    public static function form(Form $form): Form
    {
        $prices = \App\Models\Price::all();
        $price = 0;
        if($prices->count() == 1){
            $price = $prices->first()->price;
        }
        return $form
            ->schema([
                TextInput::make('weight')
                    ->label('Berat')
                    ->suffix("/Kg")
                    ->placeholder("20")
                    ->reactive()
                    ->numeric()
                    ->afterStateUpdated(fn ($state, callable $set) => $set('total', $state * $price))
                    ->required(),
                TextInput::make('price')
                    ->label('Harga Saat Ini')
                    ->default($price)
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('weight')
                    ->label('Berat')
                    ->formatStateUsing(fn (string $state): string => number_format($state, 0, ',', '.') . "Kg"),
                TextColumn::make('price')
                    ->label('Harga')
                    ->formatStateUsing(fn (string $state): string => "Rp. " . number_format($state, 0, ',', '.') . " /Kg"),
                TextColumn::make('total')
                    ->formatStateUsing(fn (string $state): string => "Rp. " . number_format($state, 0, ',', '.')),
                TextColumn::make('created_at')->formatStateUsing(fn (string $state): string => Date($state))->label('Di Ajukan Pada'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()->hidden(fn($record) => HistorySales::where('sale_id', $record->id)->first() != null),
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
            'index' => Pages\ListSalesProposals::route('/'),
            'create' => Pages\CreateSalesProposal::route('/create'),
            'view' => Pages\ViewSalesProposal::route('/{record}'),
            'edit' => Pages\EditSalesProposal::route('/{record}/edit'),
        ];
    }

}
