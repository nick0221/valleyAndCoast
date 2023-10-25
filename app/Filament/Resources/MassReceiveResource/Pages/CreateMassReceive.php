<?php

namespace App\Filament\Resources\MassReceiveResource\Pages;

use App\Filament\Resources\MassReceiveResource;
use App\Models\Inventory;
use App\Models\ReceivedStock;

use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class CreateMassReceive extends CreateRecord
{
    protected static string $resource = MassReceiveResource::class;


    protected function afterCreate(): void
    {
        $items = ReceivedStock::where('mass_receive_id', $this->getRecord()->id)->get();
        foreach ($items as $item){
            DB::table('inventories')
                ->where('id', '=', $item->inventory_id)
                ->increment('remainingStocks', $item->qty);


        }

//
//        $massReceiveId = $this->getRecord()->id;
//        ReceivedStock::where('mass_receive_id', $massReceiveId)->get()->each(function ($item) {
//            $item->inventory()->increment('remainingStocks', $item->qty);
//        });


    }

    public function getTitle(): string|Htmlable
    {
        return 'Receive Transaction';
    }


    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        return $data;
    }

}
