<?php

namespace App\Filament\Resources\InventoryResource\RelationManagers;

use App\Models\Inventory;
use App\Models\MassReceive;
use App\Models\ReceivedStock;
use App\Models\Staff;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReceivedStocksRelationManager extends RelationManager
{
    protected static string $relationship = 'received_stocks';

   protected function getTableHeading(): string|Htmlable|null
   {
       return 'Receive Transaction Logs';
   }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('inventory_id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('inventory_id')
            ->striped()
            ->columns([
                Tables\Columns\TextColumn::make('index')->rowIndex()->label('#'),

                Tables\Columns\TextColumn::make('created_at')->label('Received Date')
                        ->dateTime('M d, Y - h:i A'),

                Tables\Columns\TextColumn::make('id')
                    ->label('Ref#')
                    ->formatStateUsing(function (ReceivedStock $record){
                    $tranRef = MassReceive::where('id', $record->mass_receive_id)->first();

                    if($tranRef){
                        return $tranRef->tranReference;

                    }else{
                        return '-';
                    }

                }),

                Tables\Columns\TextColumn::make('mass_receive_id')->label('Received By')
                    ->formatStateUsing(function ($state){
                        $rcvBy = MassReceive::where('id', $state)->first();

                        if($rcvBy){
                            $staff = Staff::where('id', $rcvBy->receivedBy)->first();
                            return $staff->fullname;

                        }else{
                            return '-';
                        }

                    }),

                Tables\Columns\TextColumn::make('tranType')->label('Transaction Type'),
                Tables\Columns\TextColumn::make('qty'),
            ])
            ->filters([
                //
            ])
            ->headerActions([

            ])
            ->actions([
//                Tables\Actions\EditAction::make(),
//                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
//                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
//                ]),
            ]);
    }
}
