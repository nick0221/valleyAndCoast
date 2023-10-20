<?php

namespace App\Filament\Resources\ReservationResource\Widgets;

use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ReservationChart extends ChartWidget
{

    protected static ?string $heading = "Monthly Earnings";
    protected static ?string $description = '12 months running';
    protected static string $color = 'info';

//
//
//    protected function getFilters(): ?array
//    {
//        return [
//            'today' => 'Today',
//            'week' => 'Last week',
//            'month' => 'Last month',
//            'year' => 'This year',
//        ];
//    }


    protected function getData(): array
    {

        $chartMonthly = DB::table('additional_charges')
            ->selectRaw('
                    DATE_FORMAT(
                        reservation_accommodations.created_at,
                        "%b"
                    ) AS labelMonth,
                    SUM(additional_charges.chargePrice) AS ttlCharge,
                    SUM(reservation_accommodations.totalAmtDue) AS accomPrice

            ')
            ->rightJoin('reservation_accommodations', 'reservation_accommodations.reservation_id', '=', 'additional_charges.reservation_id')
            ->leftJoin('reservations', 'reservations.id', '=', 'reservation_accommodations.reservation_id')
            ->where('paymentStatus', '=', 'Settled')
            ->whereYear('reservation_accommodations.created_at', now()->year)
            ->groupBy('labelMonth')

            ->get();


        $monthsLabel[] = null;
        $dataVal[] = null;
        foreach ($chartMonthly as $monthly){ $monthsLabel[] = $monthly->labelMonth; }
        foreach ($chartMonthly as $data){
            $ttlCharge = (empty($data->ttlCharge)) ? 0 : $data->ttlCharge;
            $due = (empty($data->accomPrice)) ? 0 : $data->accomPrice;

            $dataVal[] = ($due+$ttlCharge);
        }
        //dd($dataVal);


        return [
            'datasets' => [
                [

                    'label' => 'Earnings',
                    'data' => $dataVal,
                    'fill' => true,
                    'tension' => 0.3,


                ],
            ],
            'labels' => $monthsLabel,


        ];
    }

    protected function getType(): string
    {
        return 'line';
    }




    protected function getOptions(): RawJs
    {
        return RawJs::make(<<<JS
        {
            plugins: {


                datalabels:{
                    'display': true,
                    'anchor': 'end',

                },

            },
            scales: {
                y: {
                    gridLines: {
                        display: false
                    },
                   beginAtZero: true, //minimum tick
                   position: 'left',
                   ticks: {
                        beginAtZero: true, //minimum tick
                        callback: (value) =>  '₱ ' + Number((value / 1000).toString()).toFixed(2) + 'K',

                   },

                },


            },


        }
    JS);
    }





}
