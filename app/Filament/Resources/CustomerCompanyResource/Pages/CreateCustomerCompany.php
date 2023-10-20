<?php

namespace App\Filament\Resources\CustomerCompanyResource\Pages;

use App\Filament\Resources\CustomerCompanyResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomerCompany extends CreateRecord
{
    protected static string $resource = CustomerCompanyResource::class;
}
