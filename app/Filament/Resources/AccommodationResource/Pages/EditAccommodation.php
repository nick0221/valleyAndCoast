<?php

namespace App\Filament\Resources\AccommodationResource\Pages;

use App\Filament\Resources\AccommodationResource;
use App\Models\Accommodation;
use App\Models\ReservationAccommodation;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditAccommodation extends EditRecord
{
    protected static string $resource = AccommodationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }


    /**
     * @param array $data
     * @return array|mixed[]
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['last_edited_by_id'] = auth()->id();
        return $data;
    }


    protected function beforeValidate(): void
    {
        //$checkExistinBookings = ReservationAccommodation::where('accommodation_id', $this->getRecord()->id)->leftJoin('accommodations', 'accommodations.id', '=', 'reservation_accommodations.accommodation_id');

//        if($checkExistinBookings->count() >= 1 && $this->getRecord()->availability == false){
//            Notification::make()
//                ->danger()
//                ->title('Restricted')
//                ->body('There is an active booking records that allocated on this room accommodation, Any changes on the details are not applied.')
//                ->duration(10000)
//                ->send();
//            $this->halt();
//        }




    }


    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }


    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Accommodation update')
            ->body('Accommodation details has been successfully updated.');
    }

}
