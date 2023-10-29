<?php

namespace App\Filament\Resources\CustomerReservationResource\Pages;

use App\Filament\Resources\CustomerReservationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCustomerReservation extends EditRecord
{
    protected static string $resource = CustomerReservationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
