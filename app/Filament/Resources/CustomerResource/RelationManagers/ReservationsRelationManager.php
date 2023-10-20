<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use App\Filament\Resources\ReservationResource\Pages\ViewReservation;
use App\Models\Reservation;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReservationsRelationManager extends RelationManager
{
    protected static string $relationship = 'reservations';


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('customer_id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('customer_id')
            ->columns([
                Tables\Columns\TextColumn::make('tranReference')->label('Reference'),
                Tables\Columns\TextColumn::make('checkIn')
                    ->label('Check In date')
                    ->date(),

                Tables\Columns\TextColumn::make('checkOut')
                    ->label('Check Out date')
                    ->date(),


                Tables\Columns\TextColumn::make('calculatedDays')->label('Length of Stay')->alignCenter()
                    ->icon('heroicon-o-calendar-days')
                    ->state(function (Model $record): string {
                        $checkIn = $record->checkIn;
                        $checkOut = $record->checkOut;
                        $diffInDays = $checkIn->diffInDays($checkOut)+1;
                        $calDays = ($diffInDays <= 1) ? "{$diffInDays}D" : "{$diffInDays}D  ".($diffInDays-1)."N";
                        return  $calDays;
                    }),

                Tables\Columns\TextColumn::make('status')->label('Booking Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Reserved' => 'warning',
                        'Confirmed' => 'success',
                        'Cancelled' => 'danger',

                    }),

                Tables\Columns\TextColumn::make('paymentStatus')->label('Payment Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Pending Payment' => 'warning',
                        'Settled' => 'success',
                        'Voided' => 'danger',
                    })
                    ->searchable(),


            ])
            ->filters([
                //
            ])
            ->headerActions([

            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('view')
                        ->url(fn (Reservation $record): string => route('filament.admin.resources.reservations.view', $record))
                        ->openUrlInNewTab()
                        ->outlined()
                        ->label('View more details'),

                    Tables\Actions\Action::make('PrintInvoice')
                        ->color('info')
                        ->hidden(
                            function (Reservation $record){
                                return ($record->paymentStatus !== 'Settled');
                            }
                        )
                        ->url(fn (Reservation $record): string => route('print.invoice', $record))
                        ->openUrlInNewTab()
                        ->outlined(),

                ])->icon('heroicon-o-ellipsis-vertical')->color('info'),

            ])
            ->emptyStateIcon('heroicon-o-face-frown')
            ->emptyStateHeading('No related records found.');
    }

    protected function getTableHeading(): string
    {
        return 'Related Records';
    }

}
