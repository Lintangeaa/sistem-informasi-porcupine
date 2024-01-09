<?php

namespace App\Filament\Resources\SalesProposalResource\Pages;

use App\Filament\Resources\SalesProposalResource;
use App\Models\HistorySales;
use App\Models\Price;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
class EditSalesProposal extends EditRecord
{
    protected static string $resource = SalesProposalResource::class;
    protected static ?string $title = "Edit Pengajuan Penjualan";

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\Action::make('advance')
                ->action(function ($data) {
                    $data['sale_id'] = $this->data['id'];
                    $data['user_id'] = $this->data['user_id'];
                    HistorySales::create($data);
                    
                })
                ->hidden(fn($record) => HistorySales::where('sale_id', $record->id)->first() != null || auth()->user()->role == "vendor")
                ->successNotificationTitle('Success ya')
                ->failureNotificationTitle('Failure ya')
                ->label('Approval')
                ->modalHeading('Approval Pengajuan Penjualan')
                ->form(function($record) {
                    return [
                        TextInput::make('weight')
                            ->label('Berat Diajukan')
                            ->default($record['weight'])
                            ->numeric()
                            ->suffix('/Kg')
                            ->readOnly(),
                        TextInput::make('accepted_weight')
                            ->label('Berat Yang Diterima/Ditolak')
                            ->suffix("/Kg")
                            ->placeholder("20")
                            ->reactive()
                            ->numeric()
                            ->default($record['weight'])
                            ->afterStateUpdated(fn ($state, callable $set) => $set('accepted_total', $state * \App\Models\Price::all()->first()->price) ?? 1)
                            ->required(),
                        TextInput::make('accepted_price')
                            ->label('Harga Saat Ini')
                            ->default(\App\Models\Price::all()->first()->price ?? 1)
                            ->numeric()
                            ->suffix('/Kg')
                            ->readOnly(),
                        TextInput::make('accepted_total')
                            ->prefix('Rp. ')
                            ->label('Total Penjualan')
                            ->default($record['total'])
                            ->numeric()
                            ->readOnly(),
                        Select::make('status')
                            ->options(['accepted' => 'Terima', 'decline' => 'Tolak'])
                            ->default('accepted')
                            ->hiddenOn('create')
                    ];
                }),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $history = HistorySales::where('sale_id', $data['id'])->first();
        if($history) {
            $data['status'] = $history->status;
        }
        $data['accepted_weight'] = $data['weight'];
        return $data;
    }


    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if($data['status'] && HistorySales::where('sale_id', $record['id'])->count() == 0) {
            $currPrice = Price::all()->first()->price;
            HistorySales::create([
                'sale_id' => $record['id'],
                'user_id' => $record['user_id'],
                'accepted_weight' => $data['weight'],
                'accepted_price' => $currPrice,
                'accepted_total' => $currPrice * $data['weight'],
                'status' => $data['status'],
            ]);
        }

        $record->update($data);

        return $record;
    }

}
