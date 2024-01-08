<?php

namespace App\Filament\Resources\HistorySalesResource\Pages;

use App\Filament\Resources\HistorySalesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHistorySales extends ListRecords
{
    protected static string $resource = HistorySalesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
