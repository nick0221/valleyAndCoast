<?php

namespace App\Filament\Resources\IssuedTransactionResource\Pages;

use App\Filament\Resources\IssuedTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateIssuedTransaction extends CreateRecord
{
    protected static string $resource = IssuedTransactionResource::class;
}
