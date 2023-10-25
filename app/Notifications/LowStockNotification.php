<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowStockNotification extends Notification
{
    use Queueable;

    protected $lowStockItems;

    /**
     * Create a new notification instance.
     *
     * @param array $lowStockItems
     */
    public function __construct(array $lowStockItems)
    {
        $this->lowStockItems = $lowStockItems;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'low_stock_items' => $this->formatLowStockItems($this->lowStockItems),
        ];
    }

    /**
     * Format the low stock items for database storage.
     *
     * @param array $lowStockItems
     * @return array
     */
    protected function formatLowStockItems($lowStockItems)
    {
        $formattedItems = [];

        foreach ($lowStockItems as $item) {
            $formattedItems[] = [
                'product_name' => $item['itemname'],
                'current_stock' => $item['remainingStocks'],

            ];
        }

        return $formattedItems;
    }
}
