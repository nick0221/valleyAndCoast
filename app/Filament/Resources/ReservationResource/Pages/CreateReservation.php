<?php

namespace App\Filament\Resources\ReservationResource\Pages;

use App\Filament\Resources\ReservationResource;
use App\Models\Accommodation;
use App\Models\Reservation;
use App\Models\ReservationAccommodation;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateReservation extends CreateRecord
{
    protected static string $resource = ReservationResource::class;


    protected function afterCreate(): void
    {
       $record =  ReservationAccommodation::find($this->getRecord()->id);

       $accomodationInfo = Accommodation::find($record->accommodation_id);
       $accomodationInfo->availability = 0;
       $accomodationInfo->save();


        $checkIn = Carbon::parse($this->getRecord()->checkIn);
        $checkOut = Carbon::parse($this->getRecord()->checkOut);
        $diffInDays = $checkIn->diffInDays($checkOut);
        $numOfCalculate = ($diffInDays > 1) ? $diffInDays : 1;

        $record->totalAmtDue = ($record->accommodationPrice * $numOfCalculate);
        $record->save();


    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $countRecords = Reservation::count();
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $result = '';
        for ($i = 0; $i < 5; $i++)
        $result .= $characters[mt_rand(0, 25)];




        $data['user_id'] = auth()->id();
        $data['status'] = "Confirmed";
        $data['paymentStatus'] = "Pending Payment";
        $data['tranReference'] = strtoupper($result). "-".date('mdY');
        $data['reservationAccommodation']['accommodation']['availability'] = 0;

//        dd($data);

        return $data;

    }

    protected function getCreatedNotificationMessage(): ?string
    {
        return 'New Booking information has been created.';
    }

}
