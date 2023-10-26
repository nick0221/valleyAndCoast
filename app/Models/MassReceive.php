<?php

namespace App\Models;

use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class MassReceive extends Model
{
    use HasFactory;

    protected $fillable = [
        'tranReference',
        'notes',
        'receivedBy',
        'user_id',
        'tranStatus',
        'supplier_id',
    ];


    public function receiveBy():BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    public function staff():BelongsTo
    {
        return $this->belongsTo(Staff::class, 'receivedBy');
    }


    public function receivedStock(): HasMany
    {
        return $this->hasMany(ReceivedStock::class, 'mass_receive_id');
    }


    public function itemInventory(): HasMany
    {
        return $this->hasMany(ReceivedStock::class, 'mass_receive_id');
    }


    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }






    public function voidTransaction($record):void
    {

        $voidRec = MassReceive::find($record->id);
        $voidRec->tranStatus = 0;
        $voidRec->notes = 'Void last '.now()->format('M d, Y - h:ia');
        $voidRec->save();

        $receivedStockItem = ReceivedStock::where('mass_receive_id', $record->id)->get();
        foreach ($receivedStockItem as $item){

            Inventory::where('id', $item->inventory_id)->decrement('remainingStocks', $item->qty);
            $item->remarks = 'Void ('.$item->qty.') qty';
            $item->qty = 0;
            $item->tranType = 'Void last '.now()->format('M d, Y - h:ia');
            $item->save();

        }

        $recipient = auth()->user();
        Notification::make()
            ->title('Void Transaction')
            ->body($voidRec->tranReference.' has been successfully void.')
            ->actions([
                \Filament\Notifications\Actions\Action::make('view')->label('View record')
                    ->button()->outlined()
                    ->markAsRead()
                    ->url(fn (): string => route('filament.admin.resources.mass-receives.view', ['record' => $record])),

                \Filament\Notifications\Actions\Action::make('markAsRead')
                    ->markAsRead(),
            ])
            ->icon('heroicon-o-archive-box-x-mark')
            ->iconColor('danger')
            ->sendToDatabase($recipient)
            ->send();

        //dd($receivedStockItem);



    }








    protected static function booted(): void
    {
        static::created(function ($massReceive) {
            $massReceive->tranReference = 'RCV-'.now('Asia/Manila')->timestamp;
            $massReceive->save();
        });


    }
}
