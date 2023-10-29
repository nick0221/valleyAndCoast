<?php

namespace App\Filament\Resources\ReservationResource\Widgets;

use Filament\Widgets\WidgetConfiguration;
use Kenepa\MultiWidget\MultiWidget;

class MultiOverview extends MultiWidget
{
    public array $widgets = [
        LatestBooking::class,
        Overview::class,
        MonthlyEarnings::class,
        DailyEarnings::class,

    ];


    public function shouldPersistMultiWidgetTabsInSession(): bool
    {
        return true;
    }



}
