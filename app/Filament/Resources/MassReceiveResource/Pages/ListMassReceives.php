<?php

namespace App\Filament\Resources\MassReceiveResource\Pages;

use App\Filament\Resources\MassReceiveResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMassReceives extends ListRecords
{
    protected static string $resource = MassReceiveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
