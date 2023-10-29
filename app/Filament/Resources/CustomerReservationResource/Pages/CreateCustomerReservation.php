<?php

namespace App\Filament\Resources\CustomerReservationResource\Pages;

use App\Filament\Resources\CustomerReservationResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomerReservation extends CreateRecord
{
    protected static string $resource = CustomerReservationResource::class;
}
