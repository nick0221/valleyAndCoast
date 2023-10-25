<?php

namespace App\Filament\Resources\MassReceiveResource\Pages;

use App\Events\InitialStockAdd;
use App\Filament\Resources\MassReceiveResource;
use App\Models\Inventory;
use App\Models\ReceivedStock;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\View\View;

class CreateMassReceive extends CreateRecord
{
    protected static string $resource = MassReceiveResource::class;

    protected function afterCreate(): void
    {
        ReceivedStock::where('mass_receive_id', $this->getRecord()->id)
            ->get()
            ->each(function($item) {
                Inventory::where('id', $item->inventory_id)->increment('remainingStocks', $item->qty);
            });

    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        return $data;
    }

}
