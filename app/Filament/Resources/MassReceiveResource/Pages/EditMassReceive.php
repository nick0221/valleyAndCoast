<?php

namespace App\Filament\Resources\MassReceiveResource\Pages;

use App\Filament\Resources\MassReceiveResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMassReceive extends EditRecord
{
    protected static string $resource = MassReceiveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
