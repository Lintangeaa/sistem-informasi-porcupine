<?php

namespace App\Filament\Resources\HistorySalesResource\Pages;

use App\Filament\Resources\HistorySalesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHistorySales extends EditRecord
{
    protected static string $resource = HistorySalesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
