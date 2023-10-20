<?php

namespace App\Filament\Resources\CustomerCompanyResource\Pages;

use App\Filament\Resources\CustomerCompanyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCustomerCompany extends EditRecord
{
    protected static string $resource = CustomerCompanyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
