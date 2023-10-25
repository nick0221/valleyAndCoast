<?php

namespace App\Providers;

use App\Models\Inventory;

use Couchbase\User;
use Filament\Notifications\DatabaseNotification;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class LowStockCheckServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $lowStockThreshold = env('LOW_STOCK_THRESHOLD', 5);
        $lowStockItems = Inventory::whereBetween('remainingStocks', [1, $lowStockThreshold])->get();

        if ($lowStockItems->isNotEmpty()) {
            // Check if the notification has already been sent
            $notif = DB::table('notifications')->where('type', DatabaseNotification::class)->exists();

            if (!$notif) {
                $strItem = ($lowStockItems->count() >= 1) ? 'item is': 'items are ';
                Notification::make()
                    ->title('Stock Level')
                    ->body($lowStockItems->count()." {$strItem} low stocks, Please check your inventories.")
                    ->actions([
                        \Filament\Notifications\Actions\Action::make('view')->label('View inventories')
                            ->markAsRead()
                            ->button()->outlined()
                            ->url(fn (): string => url('admin/inventories')),

                        \Filament\Notifications\Actions\Action::make('markAsRead')
                            ->markAsRead(),
                    ])
                    ->color('danger')
                    ->duration(10000)
                    ->icon('heroicon-o-archive-box-arrow-down')
                    ->iconColor('danger')
                    ->sendToDatabase(\App\Models\User::first());
            }


        }




    }
}
