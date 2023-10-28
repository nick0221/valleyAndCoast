<?php

namespace App\Filament\Resources\InventoryResource\RelationManagers;

use App\Models\IssuedItem;
use App\Models\IssuedTransaction;
use App\Models\MassReceive;
use App\Models\Staff;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class IssuedItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'issued_items';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('inventory_id')
            ->columns([
                Tables\Columns\TextColumn::make('index')->rowIndex()->label('#'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M d, Y - h:ia'),

                Tables\Columns\TextColumn::make('id')
                    ->url(fn (Model $record): string => route('filament.admin.resources.issued-transactions.view', $record))
                    ->tooltip('Click to view transaction details')
                    ->openUrlInNewTab()
                    ->label('Ref#')
                    ->formatStateUsing(function (IssuedItem $record){
                        $tranRef = IssuedTransaction::where('id', $record->issued_transaction_id)->first();
                        if($tranRef){
                            return $tranRef->tranReference;

                        }else{
                            return '-';
                        }

                    }),
                Tables\Columns\TextColumn::make('inventory.itemname')->label('Item Name'),

                Tables\Columns\TextColumn::make('issued_transaction_id')->label('CareOf By')
                    ->formatStateUsing(function ($state){
                        $issueTran = IssuedTransaction::where('id', $state)->first();

                        if($issueTran){
                            $staff = Staff::where('id', $issueTran->issuedBy)->first();
                            return $staff->fullname;

                        }else{
                            return '-';
                        }

                    }),
                Tables\Columns\TextColumn::make('qty'),
                Tables\Columns\TextColumn::make('tranType')->badge()->label('Transaction Type'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
