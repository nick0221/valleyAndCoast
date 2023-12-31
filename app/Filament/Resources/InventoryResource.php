<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventoryResource\Pages;
//use App\Filament\Resources\InventoryResource\RelationManagers;
use App\Filament\Resources\InventoryResource\RelationManagers\IssuedItemsRelationManager;
use App\Filament\Resources\InventoryResource\RelationManagers\ReceivedStocksRelationManager;
use App\Models\Inventory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class InventoryResource extends Resource
{
    protected static ?string $model = Inventory::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $navigationGroup = 'Manage Inventories';



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
               Forms\Components\Section::make()->schema([
                   Forms\Components\TextInput::make('itemname')->label('Item Name')
                       ->columnSpan(2)
                       ->required()
                       ->maxLength(255),

                   Forms\Components\TextInput::make('unit')->label('Unit')
                       ->columnSpan(1)
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




               ])->columnSpan(4)->columns(5),

                Forms\Components\Fieldset::make()->hiddenOn('edit')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->hidden()
                            ->image()
                            ->columnSpan(2),

                        Forms\Components\TextInput::make('remainingStocks')->label('Initial Stocks')->hint(new HtmlString('<small class="italic">(Input qty if available)</small>'))

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

                Tables\Columns\TextColumn::make('unit')->label('Item Per Unit')
                    ->searchable(),

                Tables\Columns\TextColumn::make('category.categorytitle'),

                Tables\Columns\TextColumn::make('received_stock_sum_qty')
                    ->default('-')
                    ->alignCenter()
                    ->label('Received')
                    ->sum('receivedStock', 'qty')
                    ->weight(FontWeight::Bold),

                Tables\Columns\TextColumn::make('issued_item_sum_qty')
                    ->default('-')
                    ->alignCenter()
                    ->label('Issued')
                    ->sum('issuedItem', 'qty')
                    ->weight(FontWeight::Bold),

                Tables\Columns\TextColumn::make('remainingStocks')
                    ->default('-')
                    ->weight(FontWeight::Bold)
                    ->markdown()
                    ->formatStateUsing(fn($state):string => ($state == 0)?  new  HtmlString('<small>Out of Stock</small>'):$state)
                    ->color(function ($state){
                        $color = '';
                        if ($state === 0){
                            $color = 'danger';

                        }elseif ($state <= env('LOW_STOCK_THRESHOLD', 5)){
                            $color = 'warning';

                        }elseif ($state == 'Out of stock' || empty($state)){
                            $color = 'danger';

                        }else{
                            $color = 'success';
                        }
                        return  $color;
                    })
                    ->alignCenter(),




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
            ReceivedStocksRelationManager::class,
            IssuedItemsRelationManager::class

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
