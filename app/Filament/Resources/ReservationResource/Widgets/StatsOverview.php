<?php

namespace App\Filament\Resources\ReservationResource\Widgets;

use App\Models\Reservation;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {

        $settlePayments = Reservation::query()
            ->whereDate('created_at', '=', now())
            ->where('paymentStatus', 'Settled')
            ->count();

        $voidTran = Reservation::query()
            ->where('paymentStatus', 'Voided')
            ->count();

        $totalSettled = Reservation::query()
            ->where('paymentStatus', 'Settled')
            ->count();

        $checkin = Reservation::query()
            ->whereDate('checkIn', '>=', now())
            ->where('paymentStatus', 'Pending Payment')
            ->where('status', 'Confirmed')
            ->count();

        $checkout = Reservation::query()
            ->where('paymentStatus', 'Settled')
            ->where('status', 'Confirmed')
            ->whereDate('checkOut', '>=', now())
            ->count();



        return [
            Stat::make('Total Records', number_format(Reservation::query()->count()))
                ->extraAttributes(['class' => 'overlook-card rounded-xl overflow-hidden relative bg-gradient-to-tr from-gray-100 via-white to-white dark:from-gray-950 dark:to-gray-900']),

            Stat::make('Check in', $checkin)
                ->extraAttributes(['class' => 'overlook-card rounded-xl overflow-hidden relative bg-gradient-to-tr from-gray-100 via-white to-white dark:from-gray-950 dark:to-gray-900']),

            Stat::make('Check Out', $checkout)
                ->extraAttributes(['class' => 'overlook-card rounded-xl overflow-hidden relative bg-gradient-to-tr from-gray-100 via-white to-white dark:from-gray-950 dark:to-gray-900']),

            Stat::make('Booking Confirmed', Reservation::query()
                        ->whereDate('created_at', '=', now())
                        ->where('status', 'Confirmed')
                        ->count()

            )->description(now()->toFormattedDateString())->color('success')
                ->extraAttributes(['class' => 'overlook-card rounded-xl overflow-hidden relative bg-gradient-to-tr from-gray-100 via-white to-white dark:from-gray-950 dark:to-gray-900']),


            Stat::make('Settled Payments', number_format($settlePayments))
                ->description(now()->toFormattedDateString())
                ->extraAttributes(['class' => 'overlook-card rounded-xl overflow-hidden relative bg-gradient-to-tr from-gray-100 via-white to-white dark:from-gray-950 dark:to-gray-900']),


            Stat::make('Pending Payments', Reservation::query()
                       ->whereDate('created_at', '=', now())
                       ->where('paymentStatus', 'Pending Payment')
                       ->count()
            )->description(now()->toFormattedDateString())->color('warning')
                ->extraAttributes(['class' => 'overlook-card rounded-xl overflow-hidden relative bg-gradient-to-tr from-gray-100 via-white to-white dark:from-gray-950 dark:to-gray-900']),

            Stat::make('Void Transaction', number_format($voidTran))
                ->color('danger')
                ->description('Total void transactions.')
                ->extraAttributes(['class' => 'overlook-card rounded-xl overflow-hidden relative bg-gradient-to-tr from-gray-100 via-white to-white dark:from-gray-950 dark:to-gray-900']),



            Stat::make('Total Settled',number_format($totalSettled))
                ->color('success')
                ->extraAttributes(['class' => 'overlook-card rounded-xl overflow-hidden relative bg-gradient-to-tr from-gray-100 via-white to-white dark:from-gray-950 dark:to-gray-900']),



        ];
    }

    protected function getColumns():  int
    {
        return 4;
    }
}
