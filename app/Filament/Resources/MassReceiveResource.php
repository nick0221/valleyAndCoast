<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MassReceiveResource\Pages;
//use App\Filament\Resources\MassReceiveResource\RelationManagers;
use App\Models\Inventory;
use App\Models\MassReceive;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MassReceiveResource extends Resource
{
    protected static ?string $model = MassReceive::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box-arrow-down';

    protected static ?string $navigationGroup = 'Manage Inventories';

    protected static ?string $modelLabel = 'Receive Stock';
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
                    ->reorderableWithDragAndDrop(false)->columns(6)->collapsible(true)->addActionLabel('Add more item to receive'),


                ])->columnSpan(4),




            ])->columns(6);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tranReference')
                    ->searchable(),
                Tables\Columns\TextColumn::make('notes')
                    ->searchable(),
                Tables\Columns\TextColumn::make('receivedBy')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMassReceives::route('/'),
            'create' => Pages\CreateMassReceive::route('/create'),
            'edit' => Pages\EditMassReceive::route('/{record}/edit'),
        ];
    }
}
