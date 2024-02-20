<?php

namespace App\Filament\Resources\SalesProposalResource\Pages;

use App\Filament\Resources\HistorySalesResource;
use App\Filament\Resources\SalesProposalResource;
use App\Models\Price;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateSalesProposal extends CreateRecord
{
    protected static string $resource = SalesProposalResource::class;
    protected static ?string $title = "Buat Pengajuan Penjualan";
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if(auth()->user()->role == "vendor") {
            $data['user_id'] = auth()->user()->id;
        }
        return $data;
    }

}
