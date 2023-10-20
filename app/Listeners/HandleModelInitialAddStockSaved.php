<?php

namespace App\Listeners;

use App\Events\InitialStockAdd;
use App\Models\Inventory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleModelInitialAddStockSaved
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(InitialStockAdd $event): void
    {
        $item = $event->inventory;
        $existingItem = Inventory::where('id', $item->inventory_id)->first();
        if ($existingItem) {
            $existingItem->remainingStocks += $item->qty;
            $existingItem->save();
        }
    }
}
