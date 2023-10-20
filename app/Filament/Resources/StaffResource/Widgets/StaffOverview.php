<?php

namespace App\Filament\Resources\StaffResource\Widgets;

use App\Models\Staff;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StaffOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $ttlStaff = Staff::query()
            ->count();

        $resign = Staff::query()
            ->whereNotNull('dateResign')
            ->count();

        $active= Staff::query()
            ->whereNull('dateResign')
            ->count();


        return [
            Stat::make('No. of Staff', $ttlStaff)
                ->extraAttributes(['class' => 'overlook-card rounded-xl overflow-hidden relative bg-gradient-to-tr from-gray-100 via-white to-white dark:from-gray-950 dark:to-gray-900']),

            Stat::make('Resign', $resign)
                ->extraAttributes(['class' => 'text-danger overlook-card rounded-xl overflow-hidden relative bg-gradient-to-tr from-gray-100 via-white to-white dark:from-gray-950 dark:to-gray-900']),

            Stat::make('Active', $active)
                ->extraAttributes(['class' => 'text-danger overlook-card rounded-xl overflow-hidden relative bg-gradient-to-tr from-gray-100 via-white to-white dark:from-gray-950 dark:to-gray-900']),



        ];
    }
}
