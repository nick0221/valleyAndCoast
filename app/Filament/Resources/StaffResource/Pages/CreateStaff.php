<?php

namespace App\Filament\Resources\StaffResource\Pages;

use App\Filament\Resources\StaffResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateStaff extends CreateRecord
{
    protected static string $resource = StaffResource::class;


    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Confirmation')
            ->body('New Staff information has been successfully created.');
    }




}
