<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventoryResource\Pages;
//use App\Filament\Resources\InventoryResource\RelationManagers;
use App\Filament\Resources\InventoryResource\RelationManagers\ReceivedStocksRelationManager;
use App\Models\Inventory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InventoryResource extends Resource
{
    protected static ?string $model = Inventory::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $navigationGroup = 'Manage Inventories';

    protected static ?string $modelLabel = 'Item Information';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
               Forms\Components\Section::make()->schema([
                   Forms\Components\TextInput::make('itemname')->label('Item Name')
                       ->columnSpan(2)
                       ->required()
                       ->maxLength(255),

                   Forms\Components\Select::make('category_id')
                       ->relationship('category', 'categorytitle')
                       ->searchable()
                       ->preload()
                       ->columnSpan(2)
                       ->required(),

                   Forms\Components\Textarea::make('itemdesc')->label('Item Description')
                       ->rows(5)
                       ->columnSpanFull(),




               ])->columnSpan(4)->columns(4),

                Forms\Components\Fieldset::make()
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->columnSpan(2),

                        Forms\Components\TextInput::make('remainingStocks')->label('Initial Stocks')->hint('(Input qty if available)')
                            ->hiddenOn('edit')
                            ->columnSpan(2)
                            ->numeric(),


                ])->columnSpan(2),

            ])->columns(6);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')->label('#')
                    ->rowIndex(),

                Tables\Columns\TextColumn::make('itemname')
                    ->searchable(),

                Tables\Columns\TextColumn::make('category.categorytitle'),

                Tables\Columns\TextColumn::make('id')
                    ->alignCenter()
                    ->label('Received Stocks')
                    ->numeric()
                    ->formatStateUsing(fn (Inventory $record): string =>  $record->countReceived($record->id)),

                Tables\Columns\TextColumn::make('remainingStocks')
                    ->alignCenter()
                    ->numeric(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ReceivedStocksRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInventories::route('/'),
            'create' => Pages\CreateInventory::route('/create'),
            'edit' => Pages\EditInventory::route('/{record}/edit'),
        ];
    }
}
