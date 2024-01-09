<?php

namespace App\Filament\Resources\ProductionResource\Pages;

use App\Filament\Resources\HistorySalesResource;
use App\Filament\Resources\ProductionResource;
use App\Models\HistorySales;
use App\Models\Production;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
class CreateProduction extends CreateRecord
{
    protected static string $resource = ProductionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->user()->id;
        return $data;
    }
    protected function beforeCreate(): void
    {
        $totalSale = HistorySales::where('status', 'accepted')->sum('accepted_weight');
        $totalProd = Production::all()->sum('raw_weight') + $this->data['raw_weight'];
        if($totalProd > $totalSale) {
            Notification::make()
                ->warning()
                ->title('Produksi melebihi batas')
                ->body('Tidak bisa membuat data produksi karena melebihi batas')
                ->persistent()
                ->send();

            $this->halt();
        }
    }
}
