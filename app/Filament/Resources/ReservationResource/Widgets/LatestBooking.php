<?php

namespace App\Filament\Resources\ReservationResource\Widgets;

use App\Filament\Resources\ReservationResource;

use App\Models\Reservation;
use Carbon\Carbon;

use Filament\Tables;
use Filament\Tables\Actions\Action;

use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;


class LatestBooking extends BaseWidget
{


    public function table(Table $table): Table
    {
        return $table

            ->query(ReservationResource::getEloquentQuery()->whereDate('created_at', now()->toDateString()))
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at', 'desc')

            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Booking Date')
                    ->date(),

                Tables\Columns\TextColumn::make('customer.fullname')
                    ->searchable(['firstname', 'lastname'])
                    ->label('Guest')
                    ->sortable(),

                Tables\Columns\TextColumn::make('reservationAccommodation.accommodation.roomNumber')
                    ->label('Room')
                    ->sortable(),

                Tables\Columns\TextColumn::make('calDays')->label('Length of Stay')->alignCenter()
                    ->icon('heroicon-o-calendar-days')
                    ->tooltip(fn (Model $record): string => "Checkin date: ".Carbon::create($record->checkIn)->toFormattedDateString()." - ".Carbon::create($record->checkOut)->toFormattedDateString())
                    ->state(function (Model $record): string {
                        $checkIn = $record->checkIn;
                        $checkOut = $record->checkOut;
                        $diffInDays = $checkIn->diffInDays($checkOut)+1;
                        $calDays = ($diffInDays <= 1) ? "{$diffInDays}D" : "{$diffInDays}D  ".($diffInDays-1)."N";
                        return  $calDays;
                    }),


                Tables\Columns\TextColumn::make('paymentStatus')->label('Payment Status')
                    ->icon('')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Pending Payment' => 'warning',
                        'Settled' => 'success',
                        'Voided' => 'danger',
                    })
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('status')->label('Booking Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Reserved' => 'warning',
                        'Confirmed' => 'success',
                        'Cancelled' => 'danger',
                    })
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('reservationAccommodation.totalAmtDue')
                    ->money('PHP')
                    ->alignEnd()
                    ->label('Amt Due'),

                Tables\Columns\TextColumn::make('id')
                    ->alignEnd()
                    ->formatStateUsing(function(Reservation $r){
                        return $r->sumAddCharges($r->id);
                    })

                    ->label('Add\'l. Charge'),

                Tables\Columns\TextColumn::make(__('user_id'))
                    ->alignEnd()
                    ->formatStateUsing(function(Reservation $r){
                        return $r->ttlAmtDue($r->id);
                    })
                    ->label('Total'),
            ])
            ->headerActions([
                Action::make('create')->label('Create reservation')
                    ->outlined()
                    ->url(fn (): string => route('filament.admin.resources.reservations.create'))
                    ->icon('heroicon-m-plus')
            ])
            ->actions([
                Action::make('printInvoice')
                    ->iconButton()
                    ->hidden(
                        function (Reservation $record){
                            return ($record->paymentStatus !== 'Settled');
                        }
                    )
                    ->tooltip('Print Invoice')
                    ->url(fn (Reservation $record): string => route('print.invoice', $record))
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-printer')
            ], position: ActionsPosition::BeforeCells)
            ->emptyStateActions([
                Action::make('create')
                    ->label('Create reservation')
                    ->url(fn (): string => route('filament.admin.resources.reservations.create'))
                    ->icon('heroicon-m-plus')
                    ->button(),
            ]);
    }





}



