<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IssuedTransactionResource\Pages;
use App\Filament\Resources\IssuedTransactionResource\RelationManagers;
use App\Models\Inventory;
use App\Models\IssuedTransaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class IssuedTransactionResource extends Resource
{
    protected static ?string $model = IssuedTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationGroup = 'Manage Inventories';

    protected static int $globalSearchResultsLimit = 10;


    protected static ?string $recordTitleAttribute = 'tranReference';


    protected static ?string $modelLabel = 'Issue new item';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make()->schema([
                    Forms\Components\Select::make('issuedBy')->label('Care of By')
                        ->relationship('staff', 'fullname')
                        ->searchable()
                        ->preload()

                        ->required(),

                    Forms\Components\Textarea::make('notes')
                        ->rows(5)
                        ->maxLength(255),
                ])->columnSpan(2)->columns(1),


                Forms\Components\Section::make()->schema([
                    Forms\Components\Repeater::make('issuedItems')
                        ->relationship()
                        ->schema([
                        Forms\Components\Select::make('inventory_id')->label('Item Name')
                            ->relationship('inventory', 'itemname')
                            ->required()
                            ->searchable()
                            ->columnSpan(4)
                            ->live()
                            ->preload(),

                        Forms\Components\TextInput::make('issuedQty')->label('Qty')
                            ->numeric()
                            ->columnSpan(2),

                    ])
                    ->itemLabel(fn (array $state): ?string =>
                       Inventory::query()->where('id', $state['inventory_id'])->first()->itemname ?? null
                    )
                    ->reorderableWithDragAndDrop(false)->columns(6)->collapsible()->addActionLabel('Add more items')

                ])->columnSpan(4),




            ])->columns(6);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')->rowIndex()->label('#'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M d, Y - h:iA'),

                Tables\Columns\TextColumn::make('tranReference')->label('Transaction Ref#')
                    ->searchable(),

                Tables\Columns\TextColumn::make('careOfBy.fullname')->label('CareOf by'),

                Tables\Columns\TextColumn::make('notes')
                    ->default('-')
                    ->searchable(),

                Tables\Columns\TextColumn::make('tranStatus')->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '1' => 'success',
                        '0' => 'danger',

                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        '1' => 'Success',
                        '0' => 'Void',

                    })
                    ->searchable(),




                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
//                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
//                ]),
            ]);
    }


    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Fieldset::make('')->schema([
                    TextEntry::make('created_at')
                        ->dateTime('M d, Y - h:i A')
                        ->label('Date Issued: '),

                    TextEntry::make('careOfBy.fullname')
                        ->label('CareOf By:'),

                    TextEntry::make('notes')->label('Notes: ')->default('-'),
                ])->columnSpan(1)->columns(1),


                Section::make('')->schema([
                    RepeatableEntry::make('issuedItems')->label('Item Information')->schema([
                        TextEntry::make('inventory.itemname')->label('Item Name'),
                        TextEntry::make('issuedQty'),
                    ])->columns(2)

                ])->columnSpan(4),

            ])->columns(5);

    }







    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIssuedTransactions::route('/'),
            'create' => Pages\CreateIssuedTransaction::route('/create'),
            'edit' => Pages\EditIssuedTransaction::route('/{record}/edit'),
            'view' => Pages\ViewIssuedTransaction::route('/{record}'),
        ];
    }



    public static function getGlobalSearchResultUrl(Model $record): string
    {
        return IssuedTransactionResource::getUrl('view', ['record' => $record]);
    }











}
