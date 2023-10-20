<?php

namespace App\Filament\Resources\ReservationResource\Pages;

use App\Filament\Resources\ReservationResource;
use App\Models\Reservation;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewReservation extends ViewRecord
{
    protected static string $resource = ReservationResource::class;


   protected function getHeaderActions(): array
   {
       return [
           Actions\Action::make('Print Invoice')
               ->hidden(
                   function (Reservation $record){
                       return ($record->paymentStatus !== 'Settled');
                   }
               )
               ->url(fn (Reservation $record): string => route('print.invoice', $record))
               ->openUrlInNewTab()
               ->outlined()
               ->icon('heroicon-o-printer'),
       ];
   }
}
