<?php

namespace App\Filament\Resources\ReservationResource\Widgets;

use Filament\Widgets\WidgetConfiguration;
use Kenepa\MultiWidget\MultiWidget;

class MultiOverview extends MultiWidget
{
    public array $widgets = [
        LatestReservation::class,
        ReservationOverview::class,
        ReservationChart::class,
        DailyReservationIncome::class,

    ];


    public function shouldPersistMultiWidgetTabsInSession(): bool
    {
        return true;
    }



}
