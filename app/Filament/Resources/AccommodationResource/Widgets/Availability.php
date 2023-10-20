<?php

namespace App\Filament\Resources\AccommodationResource\Widgets;

use App\Models\Accommodation;
use App\Models\Reservation;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Availability extends BaseWidget
{

    protected function getStats(): array
    {

        $available = Accommodation::query()
                        ->where('availability', true)
                        ->count();

        $ttlRooms = Accommodation::query()->count();

        $isAircon = Accommodation::query()
            ->where('isAirconditioned', true)
            ->count();


        $ventilated = Accommodation::query()
            ->where('isAirconditioned', false)
            ->count();

        return [
            Stat::make('Total Accommodation', $ttlRooms)
                ->extraAttributes(['class' => 'overlook-card rounded-xl overflow-hidden relative bg-gradient-to-tr from-gray-100 via-white to-white dark:from-gray-950 dark:to-gray-900']),

            Stat::make('No. of Rooms Available', $available)
                ->extraAttributes(['class' => 'overlook-card rounded-xl overflow-hidden relative bg-gradient-to-tr from-gray-100 via-white to-white dark:from-gray-950 dark:to-gray-900']),

            Stat::make('With A/C', $isAircon)
                ->extraAttributes(['class' => 'overlook-card rounded-xl overflow-hidden relative bg-gradient-to-tr from-gray-100 via-white to-white dark:from-gray-950 dark:to-gray-900']),

            Stat::make('Ventilated', $ventilated)
                ->extraAttributes(['class' => 'overlook-card rounded-xl overflow-hidden relative bg-gradient-to-tr from-gray-100 via-white to-white dark:from-gray-950 dark:to-gray-900']),

        ];
    }
}
