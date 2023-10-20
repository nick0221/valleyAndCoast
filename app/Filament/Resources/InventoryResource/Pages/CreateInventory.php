<?php

namespace App\Filament\Resources\InventoryResource\Pages;

use App\Events\InitialStockAdd;
use App\Filament\Resources\InventoryResource;
use App\Models\ReceivedStock;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;

class CreateInventory extends CreateRecord
{
    protected static string $resource = InventoryResource::class;


    protected function afterCreate(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        //dd($this->getRecord());
        $insertInitialStock = new ReceivedStock;
        $insertInitialStock->inventory_id  = $this->getRecord()->id;
        $insertInitialStock->qty = $this->getRecord()->remainingStocks;
        $insertInitialStock->remarks = 'Added Initial qty upon register of the item.';
        $insertInitialStock->tranType = 'InitialStock';
        $insertInitialStock->save();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        //event(new InitialStockAdd($insertInitialStock));


    }


    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $data['created_by'] = auth()->id();
        return $data;
    }


    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Confirmation')
            ->body('New Item Information has been successfully created.');
    }


}
