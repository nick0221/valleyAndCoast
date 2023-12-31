<?php

namespace App\Filament\Resources\IssuedTransactionResource\Pages;

use App\Filament\Resources\IssuedTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;

class ListIssuedTransactions extends ListRecords
{
    protected static string $resource = IssuedTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }


}
