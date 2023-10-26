<?php

namespace App\Filament\Resources\IssuedTransactionResource\Pages;

use App\Filament\Resources\IssuedTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIssuedTransaction extends EditRecord
{
    protected static string $resource = IssuedTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
