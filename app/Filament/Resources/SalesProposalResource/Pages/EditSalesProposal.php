<?php

namespace App\Filament\Resources\SalesProposalResource\Pages;

use App\Filament\Resources\SalesProposalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSalesProposal extends EditRecord
{
    protected static string $resource = SalesProposalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
