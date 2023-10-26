<?php

namespace App\Filament\Resources\MassReceiveResource\Pages;

use App\Filament\Resources\MassReceiveResource;
use App\Models\MassReceive;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\View\View;

class ViewReceived extends ViewRecord
{
    protected static string $resource = MassReceiveResource::class;

   protected function getHeaderActions(): array
   {
       return [
           Actions\Action::make('Acknowledgment Receipt')
               ->hidden(
                   function (MassReceive $record){
                       return ($record->tranStatus === 0);
                   }
               )
               ->url(fn (MassReceive $record): string => route('print.receipt', $record))
               ->openUrlInNewTab()
               ->outlined()
               ->icon('heroicon-o-printer'),
       ];
   }

}
