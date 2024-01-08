<?php

namespace App\Filament\Resources\SalesProposalResource\Pages;

use App\Filament\Resources\SalesProposalResource;
use App\Models\HistorySales;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSalesProposal extends ViewRecord
{
    protected static string $resource = SalesProposalResource::class;
    protected static ?string $title = "Pengajuan Penjualan";

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->hidden(fn($record) => HistorySales::where('sale_id', $record->id)->first() != null),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $history = HistorySales::where('sale_id', $data['id'])->first();
        if($history) {
            $data['status'] = $history->status;
        }
        return $data;
    }
}
