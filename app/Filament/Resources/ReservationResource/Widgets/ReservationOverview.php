<?php

namespace App\Filament\Resources\ReservationResource\Widgets;

use App\Models\AdditionalCharges;
use App\Models\Reservation;
use App\Models\ReservationAccommodation;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class ReservationOverview extends BaseWidget
{


    protected function getStats(): array
    {
        $chartMonthly = DB::table('additional_charges')
            ->selectRaw('
                    MONTH(reservation_accommodations.created_at) AS monthly,
                    SUM(additional_charges.chargePrice) AS ttlCharge,
                    SUM(reservation_accommodations.totalAmtDue) AS accomPrice


            ')

            ->rightJoin('reservation_accommodations', 'reservation_accommodations.reservation_id', '=', 'additional_charges.reservation_id')
            ->leftJoin('reservations', 'reservations.id', '=', 'reservation_accommodations.reservation_id')
            ->where('paymentStatus', '=', 'Settled')
            ->whereYear('reservation_accommodations.created_at',  date('Y'))
            ->groupBy('monthly')
            ->orderBy('monthly', 'ASC')
            ->limit(12)
            ->get();


        $dataVal = array();
        foreach ($chartMonthly as $data){
            $charge = $data->ttlCharge;
            $accom = $data->accomPrice;
            $ttl = $charge+$accom;
            $dataVal[] = (empty($ttl) || 0) ? 0 : $ttl;
        }
//        dd($chartMonthly);

        $ttlEarnings = array_sum($dataVal);
        function formatLargeNumber($number) {
            $suffix = '';
            $divisors = [1 => '', 1000 => 'K', 1000000 => 'M', 1000000000 => 'B'];
            foreach ($divisors as $divisor => $divisorSuffix) {
                if ($number >= $divisor) {
                    $number = $number / $divisor;
                    $suffix = $divisorSuffix;
                }
            }
            return '₱' . number_format($number, 2) . $suffix;
        }

        $forCollections = ReservationAccommodation::query()
            ->leftJoin('reservations', 'reservations.id', '=', 'reservation_accommodations.reservation_id')
            ->where('paymentStatus', 'Pending Payment')
            ->sum('totalAmtDue');

        $cashEarnings = ReservationAccommodation::query()
            ->leftJoin('reservations', 'reservations.id', '=', 'reservation_accommodations.reservation_id')
            ->leftJoin('payment_details', 'payment_details.reservation_id', '=', 'reservation_accommodations.reservation_id')
            ->where('paymentStatus', 'Settled')
            ->where('payMethod', 'Cash')
            ->sum('totalAmtDue');

        $gcashEarnings = ReservationAccommodation::query()
            ->leftJoin('reservations', 'reservations.id', '=', 'reservation_accommodations.reservation_id')
            ->leftJoin('payment_details', 'payment_details.reservation_id', '=', 'reservation_accommodations.reservation_id')
            ->where('reservations.paymentStatus', 'Settled')
            ->where('payment_details.payMethod', 'Gcash')
            ->sum('totalAmtDue');

        $cardEarnings = ReservationAccommodation::query()
            ->leftJoin('reservations', 'reservations.id', '=', 'reservation_accommodations.reservation_id')
            ->leftJoin('payment_details', 'payment_details.reservation_id', '=', 'reservation_accommodations.reservation_id')
            ->where('paymentStatus', 'Settled')
            ->where('payMethod', 'Card')
            ->sum('totalAmtDue');

        $additionalCharges = AdditionalCharges::query()
            ->sum('chargePrice');


//        dd($gcashEarnings);


        return [
            Stat::make('Total Records', Reservation::query()->count())
                ->extraAttributes(['class' => 'overlook-card rounded-xl overflow-hidden relative bg-gradient-to-tr from-gray-100 via-white to-white dark:from-gray-950 dark:to-gray-900']),

            Stat::make('Void Transaction', Reservation::query()
                ->where('paymentStatus', 'Voided')
                ->count()
            )->color('danger')->description('Total void transactions.')
            ->extraAttributes(['class' => 'overlook-card rounded-xl overflow-hidden relative bg-gradient-to-tr from-gray-100 via-white to-white dark:from-gray-950 dark:to-gray-900']),

            Stat::make('Total Settled', Reservation::query()
                ->where('paymentStatus', 'Settled')
                ->count()
            )->color('success')
            ->extraAttributes(['class' => 'overlook-card rounded-xl overflow-hidden relative bg-gradient-to-tr from-gray-100 via-white to-white dark:from-gray-950 dark:to-gray-900']),


            Stat::make('Pending Payments', Reservation::query()
                ->whereDate('created_at', '=', now())
                ->where('paymentStatus', 'Pending Payment')
                ->count()
            )->description(now()->toFormattedDateString())->color('warning')
            ->extraAttributes(['class' => 'overlook-card rounded-xl overflow-hidden relative bg-gradient-to-tr from-gray-100 via-white to-white dark:from-gray-950 dark:to-gray-900']),


            Stat::make('Earnings', formatLargeNumber($ttlEarnings))
                ->chart($dataVal)
                ->color('success')
                ->extraAttributes(['class' => 'overlook-card rounded-xl overflow-hidden relative bg-gradient-to-tr from-gray-100 via-white to-white dark:from-gray-950 dark:to-gray-900']),


            Stat::make('For Collections', formatLargeNumber($forCollections))
                ->extraAttributes(['class' => 'overlook-card rounded-xl overflow-hidden relative bg-gradient-to-tr from-gray-100 via-white to-white dark:from-gray-950 dark:to-gray-900']),


            Stat::make('Cash Earnings', formatLargeNumber($cashEarnings))
                ->extraAttributes(['class' => 'overlook-card rounded-xl overflow-hidden relative bg-gradient-to-tr from-gray-100 via-white to-white dark:from-gray-950 dark:to-gray-900']),


            Stat::make('Gcash Earnings', formatLargeNumber($gcashEarnings))
                ->extraAttributes(['class' => 'overlook-card rounded-xl overflow-hidden relative bg-gradient-to-tr from-gray-100 via-white to-white dark:from-gray-950 dark:to-gray-900']),


            Stat::make('Card Earnings', formatLargeNumber($cardEarnings))
                ->extraAttributes(['class' => 'overlook-card rounded-xl overflow-hidden relative bg-gradient-to-tr from-gray-100 via-white to-white dark:from-gray-950 dark:to-gray-900']),


            Stat::make('Earnings for Additional Charges', formatLargeNumber($additionalCharges))
                ->extraAttributes(['class' => 'overlook-card rounded-xl overflow-hidden relative bg-gradient-to-tr from-gray-100 via-white to-white dark:from-gray-950 dark:to-gray-900']),



        ];
    }
}
