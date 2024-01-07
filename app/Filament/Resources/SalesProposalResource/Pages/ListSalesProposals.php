<?php

namespace App\Filament\Resources\SalesProposalResource\Pages;

use App\Filament\Resources\SalesProposalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSalesProposals extends ListRecords
{
    protected static string $resource = SalesProposalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
