<?php

namespace App\Filament\Resources\MassReceiveResource\Pages;

use App\Events\InitialStockAdd;
use App\Filament\Resources\MassReceiveResource;
use App\Models\ReceivedStock;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\View\View;

class CreateMassReceive extends CreateRecord
{
    protected static string $resource = MassReceiveResource::class;

    protected function afterCreate(): void
    {
        $rcvItem = ReceivedStock::where('mass_receive_id', $this->getRecord()->id)->get();

        event(new InitialStockAdd($rcvItem));

    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        return $data;
    }

}
