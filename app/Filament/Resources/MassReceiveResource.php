<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MassReceiveResource\Pages;
//use App\Filament\Resources\MassReceiveResource\RelationManagers;
use App\Models\Inventory;
use App\Models\MassReceive;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use stdClass;

class MassReceiveResource extends Resource
{
    protected static ?string $model = MassReceive::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box-arrow-down';

    protected static ?string $navigationGroup = 'Manage Inventories';

    protected static ?string $modelLabel = 'Receiving New Stock';

    protected static ?string $recordTitleAttribute = 'tranReference';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make()->schema([



                    Forms\Components\Select::make('receivedBy')
                        ->columnSpan(2)
                        ->relationship('receiveBy', 'fullname')
                        ->searchable()
                        ->preload()
                        ->required(),

                    Forms\Components\Textarea::make('notes')
                        ->columnSpanFull(),


                ])->columnSpan(2),

                Forms\Components\Section::make('Item Information')->compact()->schema([

                    Forms\Components\Repeater::make('itemInventory')->hiddenLabel()
                        ->relationship()->schema([
                        Forms\Components\Select::make('inventory_id')
                            ->relationship('itemInfo', 'itemname')
                            ->preload()
                            ->searchable()
                            ->required()
                            ->live()
                            ->columnSpan(4),

                        Forms\Components\TextInput::make('qty')
                            ->required()
                            ->columnSpan(1),


                    ])
                    ->itemLabel(fn (array $state): ?string =>
                        Inventory::query()->where('id', $state['inventory_id'])->first()->itemname ?? null
                    )
                    ->reorderableWithDragAndDrop(false)->columns(6)->collapsible(true)->addActionLabel('Add more item to receive')
                    ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                        $data['tranType'] = 'Receive';
                        return $data;
                    }),

                ])->columnSpan(4),




            ])->columns(6);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tranReference')
                    ->searchable(),

                Tables\Columns\TextColumn::make('staff.fullname')
                    ->searchable()
                    ->label('Received By'),


                Tables\Columns\TextColumn::make('notes')
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
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }


    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist

            ->schema([
               Section::make()->schema([
                   TextEntry::make('created_at')
                       ->dateTime('M d, Y - h:i A')
                       ->label('Date Received'),

                   TextEntry::make('staff.fullname')
                       ->label('Received By'),

                   TextEntry::make('notes'),
               ])->columnSpan(1),

                Section::make('Item Information')->schema([
                    RepeatableEntry::make('receivedStock')->hiddenLabel()
                        ->schema([

                            TextEntry::make('inventory.itemname')->label('Item Name'),
                            TextEntry::make('qty'),
                            TextEntry::make('tranType'),
                            TextEntry::make('remarks')->default('-'),



                    ])->columnSpanFull()->columns(4)->contained()
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
            'index' => Pages\ListMassReceives::route('/'),
            'create' => Pages\CreateMassReceive::route('/create'),
            'edit' => Pages\EditMassReceive::route('/{record}/edit'),
            'view' => Pages\ViewReceived::route('/{record}'),
        ];
    }
}
