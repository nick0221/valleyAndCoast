<?php

namespace App\Filament\Resources\ReservationResource\RelationManagers;

use App\Models\Reservation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AdditionalChargesRelationManager extends RelationManager
{
    protected static string $relationship = 'addCharges';

    public function isReadOnly(): bool
    {
        return $this->getOwnerRecord()->paymentStatus == 'Settled';
    }


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('chargeFor')->label('Particulars')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('chargePrice')
                    ->numeric()
                    ->required()
                    ->maxLength(255),
            ]);
    }



    public function table(Table $table): Table
    {
        return $table

            ->recordTitle('Additional Charges')
            ->columns([
                Tables\Columns\TextColumn::make('chargeFor')->label('Particulars'),
                Tables\Columns\TextColumn::make('chargePrice')
                    ->money('PHP')
                    ->label('Charge Price')
                    ->summarize(Sum::make()->label('Total Charges')->money('PHP'))
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Add Charges')
                            ->body('Charges has been successfully added to reference '.$this->getOwnerRecord()->tranReference),
                    )
                    ->hidden(fn():bool => ($this->getOwnerRecord()->status == 'Cancelled')),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No additional charges.')
            ->emptyStateDescription('');
    }





}
