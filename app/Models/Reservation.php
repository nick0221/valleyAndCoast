<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Filament\Notifications\Notification;


class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'checkIn',
        'checkOut',
        'user_id',
        'last_edited_by_id',
        'status',
        'tranReference',
        'checkInTime',
        'checkOutTime',
        'guest',
        'paymentStatus'
    ];

    protected $casts = [
        'guest' => 'json',
        'checkIn' => 'date',
        'checkOut' => 'date',

    ];




    public function accommodation(): BelongsTo
    {
        return $this->belongsTo(Accommodation::class);
    }


    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }


    public function lastEditedBy(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function reservationAccommodation(): HasMany
    {
        return $this->hasMany(ReservationAccommodation::class);
    }

    public function paymentInformation(): HasMany
    {
        return $this->hasMany(PaymentDetails::class);
    }


    public function customerCompany(): BelongsTo
    {
        return $this->belongsTo(CustomerCompany::class);
    }


    public function paymentDetails(): HasOne
    {
        return $this->hasOne(PaymentDetails::class, );
    }



    public function scopeWithPaymentDetails(): HasOne
    {
        return $this->hasOne(PaymentDetails::class, );
    }

    public function addCharges(): HasMany
    {
        return $this->hasMany(AdditionalCharges::class, 'reservation_id');
    }

    public function sumAddCharges($record): ?string
    {
         $addCharges = AdditionalCharges::where('reservation_id', $record)
                ->selectRaw('SUM(chargePrice) AS sumCharge')
                ->first();

         //dd($record);

         return (empty($addCharges->sumCharge) || $addCharges->sumCharge == 0) ? null: "₱".number_format($addCharges->sumCharge, 2);
    }

    public function ttlAmtDue($record): ?string
    {
        $addCharges = AdditionalCharges::where('reservation_id', $record)
            ->selectRaw('SUM(chargePrice) AS sumCharge')
            ->first();

        $accomDue = ReservationAccommodation::where('reservation_id', $record)
            ->first();


        $ttlaAmtDue = ($addCharges->sumCharge+$accomDue->totalAmtDue);
        return (empty($ttlaAmtDue) || $ttlaAmtDue == 0) ? null: "₱".number_format($ttlaAmtDue, 2);
    }



    public function getAccommodation(): HasMany
    {
        return $this->hasMany(ReservationAccommodation::class)
                    ->leftJoin(
                        'accommodations', 'accommodations.id',
                        '=',
                        'reservation_accommodations.accommodation_id');
    }





    public function setRoomToAvailable($recordId): void
    {
        $record = Accommodation::find($recordId);
        $record->availability = 1;
        $record->save();
    }


    public function notifySuccessCheckout($record): void
    {
        $recipient = auth()->user();
        Notification::make()
            ->title('Checkout Guest')
            ->body($record->tranReference.' has been successfully checked out and settled the payments.')
            ->actions([
                \Filament\Notifications\Actions\Action::make('view')->label('View record')
                    ->markAsRead()
                    ->button()->outlined()
                    ->url(fn (): string => route('filament.admin.resources.reservations.view', ['record' => $record])),

                \Filament\Notifications\Actions\Action::make('markAsRead')
                   ->markAsRead(),
            ])
            ->color('success')
            ->duration(10000)
            ->icon('heroicon-o-check-badge')
            ->iconColor('success')
            ->sendToDatabase($recipient)
            ->send();


    }



    public function sendSuccessCancelBooking($records): void
    {
        $recipient = auth()->user();
        Notification::make()
            ->title('Booking Cancellation')
            ->body($records->tranReference.' has been successfully cancelled.')
            ->actions([
                \Filament\Notifications\Actions\Action::make('view')->label('View record')
                    ->button()->outlined()
                    ->markAsRead()
                    ->url(fn (): string => route('filament.admin.resources.reservations.view', ['record' => $records])),

                \Filament\Notifications\Actions\Action::make('markAsRead')
                    ->markAsRead(),
            ])
            ->icon('heroicon-o-archive-box-x-mark')
            ->iconColor('danger')
            ->sendToDatabase($recipient)
            ->send();
    }

    public function setAmountZero($record): void
    {
        $reserveAccom = ReservationAccommodation::find($record->id);
        $reserveAccom->accommodationPrice = 0;
        $reserveAccom->totalAmtDue = 0;
        $reserveAccom->save();

        $c = null;
        $charges = AdditionalCharges::where('reservation_id', '=', $record->id)->get();
        foreach ($charges as $charge){
            $c[] = $charge->id;
        }
        AdditionalCharges::destroy($c);

    }




    public function addChargesToGuest($record, $data): void
    {


          if (count($data['addCharges']) > 0){
              $reserve_id =  array('reservation_id' => $record->id);

              foreach ($data['addCharges'] as &$particulars){
                  // Find the index of "chargePrice" => "Sample"
                  $keyToInsertAfter = "chargePrice";
                  $indexToInsertAfter = array_search($keyToInsertAfter, array_keys($particulars));

                  // Insert $reserve_id after "chargePrice" => "Sample"
                  if ($indexToInsertAfter !== false) {
                      $particulars = array_merge(array_slice($particulars, 0, $indexToInsertAfter + 1), $reserve_id, array_slice($particulars, $indexToInsertAfter + 1));
                  }
                  $check[] = $particulars;

              }

              foreach ($check as $Addcharg){
                  // Create a new instance of AdditionalCharges model
                  $additionalCharge = new AdditionalCharges();

                  // Fill the model with data
                  $additionalCharge->fill([
                      'chargeFor' => $Addcharg['chargeFor'],
                      'chargePrice' => $Addcharg['chargePrice'],
                      'reservation_id' => $Addcharg['reservation_id']
                  ]);

                  // Save the model to the database
                  $additionalCharge->save();
              }

          }

            Notification::make()
                ->title('Booking Cancellation')
                ->body("Additional charges for booking reference {$record->tranReference} has been successfully added.")
                ->icon('heroicon-o-archive-box-x-mark')
                ->duration(10000)
                ->actions([
                    \Filament\Notifications\Actions\Action::make('view')->label('View record')
                        ->button()->outlined()
                        ->url(fn (): string => route('filament.admin.resources.reservations.view', ['record' => $record])),


                ])
                ->success()
                ->send();



    }


    public function guestAmtDue($record): float
    {
        $getAccomDue = ReservationAccommodation::where('reservation_id', '=', $record->id)->get();
        $getAddCharges = AdditionalCharges::where('reservation_id', '=', $record->id)->sum('chargePrice');

        return ($getAccomDue[0]->totalAmtDue)+$getAddCharges;

    }


    public function guestChargesDue($record): float
    {

        $getAddCharges = AdditionalCharges::where('reservation_id', '=', $record->id)->sum('chargePrice');
        return $getAddCharges;

    }






}
