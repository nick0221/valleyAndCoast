<?php

namespace App\Filament\Resources\ReservationResource\Widgets;

use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class DailyReservationIncome extends ChartWidget
{
    protected static ?string $heading = 'Daily Income';
    protected static ?string $description = '7 days running';



    protected function getData(): array
    {

        $chartdaily = DB::table('additional_charges')
            ->selectRaw('
                SUM(additional_charges.chargePrice) AS ttlCharge,
                SUM(reservation_accommodations.totalAmtDue) AS accomPrice,
                SUM(reservation_accommodations.totalAmtDue + IFNULL(additional_charges.chargePrice, 0)) AS dataVal,
               DATE_FORMAT(reservation_accommodations.created_at, "%b %d") AS dailyLabel
            ')
            ->rightJoin('reservation_accommodations', 'reservation_accommodations.reservation_id', '=', 'additional_charges.reservation_id')
            //->whereBetween('reservation_accommodations.created_at', [now()->subDays(6), now()])
            ->groupBy('dailyLabel')
            ->orderByDesc('dailyLabel')
            ->limit(7)
            ->get();

        //dd($chartdaily);

        $dailyLabel[] = null;
        $dataVal[] = null;
        foreach ($chartdaily as $daily){ $dailyLabel[] = $daily->dailyLabel; }
        foreach ($chartdaily as $data){ $dataVal[] = (empty($data->dataVal)) ? 0 : $data->dataVal; }

        return [
            'datasets' => [
                [

                    'label' => 'Total Earnings',
                    'data' => $dataVal,
                    'fill' => true,
                    'tension' => 0.3,


                ],
            ],
            'labels' => $dailyLabel,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
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
