<?php

namespace App\Filament\Resources\MassReceiveResource\Pages;

use App\Filament\Resources\MassReceiveResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\View\View;

class ViewReceived extends ViewRecord
{
    protected static string $resource = MassReceiveResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }


}
