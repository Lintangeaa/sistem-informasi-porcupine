<?php

namespace App\Filament\Resources\HistorySalesResource\Pages;

use App\Filament\Resources\HistorySalesResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewHistorySales extends ViewRecord
{
    protected static string $resource = HistorySalesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->hidden(auth()->user()->role == "vendor"),
        ];
    }
}
