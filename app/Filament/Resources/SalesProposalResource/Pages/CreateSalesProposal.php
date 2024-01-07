<?php

namespace App\Filament\Resources\SalesProposalResource\Pages;

use App\Filament\Resources\SalesProposalResource;
use App\Models\Price;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSalesProposal extends CreateRecord
{
    protected static string $resource = SalesProposalResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $price = Price::all()->first();
        $data['user_id'] = auth()->user()->id;
        $data['total'] = $price->price * $data['weight'];
        return $data;
    }
}
