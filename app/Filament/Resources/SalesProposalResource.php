<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SalesProposalResource\Pages;
use App\Filament\Resources\SalesProposalResource\RelationManagers;
use App\Models\SalesProposal;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;

class SalesProposalResource extends Resource
{
    protected static ?string $model = SalesProposal::class;

    protected static ?string $title = 'Pengajuan Penjualan';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('weight')
                    ->label('Berat')
                    ->placeholder('20')
                    ->numeric()
                    ->suffix('/Kg')
                    ->required(),

            ]);
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Berhasil mengajukan penjualan';
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('weight'),
                TextColumn::make('total'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->mutateFormDataUsing(function (array $array) {
                    return dd($array);
                }),

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
            'index' => Pages\ListSalesProposals::route('/'),
            'create' => Pages\CreateSalesProposal::route('/create'),
            'edit' => Pages\EditSalesProposal::route('/{record}/edit'),
        ];
    }
}
