<?php

namespace App\Filament\Resources\CustomerCompanyResource\Pages;

use App\Filament\Resources\CustomerCompanyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCustomerCompanies extends ListRecords
{
    protected static string $resource = CustomerCompanyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
