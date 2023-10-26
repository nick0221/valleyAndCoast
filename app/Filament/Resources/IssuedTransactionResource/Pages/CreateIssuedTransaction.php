<?php

namespace App\Filament\Resources\IssuedTransactionResource\Pages;

use App\Filament\Resources\IssuedTransactionResource;
use App\Models\Inventory;
use App\Models\IssuedItem;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateIssuedTransaction extends CreateRecord
{
    protected static string $resource = IssuedTransactionResource::class;




    protected ?string $heading = 'Issue New Items';

    protected function afterCreate(): void
    {

        $issuedTransaction = $this->getRecord()->id;

        IssuedItem::where('issued_transaction_id', $issuedTransaction)->get()->each(function ($item) {
            Inventory::where('id', $item->inventory_id)->decrement('remainingStocks', $item->issuedQty);
        });


    }


    protected function beforeCreate(): void
    {
//        if (! $this->getRecord()->team->subscribed()) {
//            Notification::make()
//                ->warning()
//                ->title('Item Stocks Insufficient')
//                ->body('You are issuing an item that is more than your current stocks, kindly check the inventory first before issuing.')
//                ->persistent()
//                ->send();
//
//            $this->halt();
//        }
    }



    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        return $data;
    }



    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Confirmation')
            ->body('Stocks has been successfully issued.');
    }


}
