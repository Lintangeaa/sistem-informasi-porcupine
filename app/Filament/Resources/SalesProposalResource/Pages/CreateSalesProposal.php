<?php

namespace App\Filament\Resources\SalesProposalResource\Pages;

use App\Filament\Resources\SalesProposalResource;
use App\Models\Price;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSalesProposal extends CreateRecord
{
    protected static string $resource = SalesProposalResource::class;
    protected static ?string $title = "Buat Pengajuan Penjualan";
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->user()->id;
        return $data;
    }
}
