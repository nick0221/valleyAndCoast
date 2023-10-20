<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccommodationResource\Pages;
use App\Filament\Resources\AccommodationResource\RelationManagers;
use App\Models\Accommodation;
use App\Models\Amenities;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AccommodationResource extends Resource
{
    protected static ?string $model = Accommodation::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Accommodation Information')
                    ->description('Please fill out the all the required fields.')
                    ->icon('heroicon-s-home-modern')
                    ->schema([
                    Forms\Components\TextInput::make('roomNumber')->label('Room Title')

                        ->required()
                        ->columnSpan(6)
                        ->maxLength(255),

                    Forms\Components\Select::make('bed_type_id')
                        ->relationship('bedType', 'title')
                        ->columnSpan(4)
                        ->required(),

                    Forms\Components\TextInput::make('bedCount')
                        ->columnSpan(2)
                        ->numeric(),

                    Forms\Components\TextInput::make('maxOccupancy')
                        ->numeric()
                        ->columnSpan(3)
                        ->maxLength(255),


                    Forms\Components\TextInput::make('roomSize')
                        ->placeholder('12sq')
                        ->columnSpan(3)
                        ->maxLength(255),

                    Forms\Components\TextInput::make('pricePerNight')->label('Rate Per Night')
                        ->prefix('')
                        ->required()
                        ->columnStart(10)
                        ->columnSpan(3)
                        ->numeric(),



                    Forms\Components\Toggle::make('isSmokingAllowed')
                        ->columnSpan(4)
                        ->hidden(),

                    Forms\Components\Toggle::make('hasBalcony')
                        ->columnSpan(4)
                        ->hidden(),

                    Forms\Components\Toggle::make('isAirconditioned')
                        ->columnSpan(4)
                        ->required(),



                ])->columns('12')->columnSpan(9),

                Forms\Components\Fieldset::make()
                    ->schema([
                    Forms\Components\FileUpload::make('image')
                        ->columnSpan(3)
                        ->previewable()
                        ->image()
                        ->imageEditor()
                        ->directory('hotel-image'),

                    Forms\Components\Toggle::make('availability')->label('Availability of Room')
                        ->default(1)
                        ->columnSpan(3)
                        ->required(),





                ])->columnSpan(3)->columns(1),

                Forms\Components\Section::make()->schema([
                    Forms\Components\Textarea::make('description')->label('Accommodation Description')
                        ->columnSpan(12),

                    Forms\Components\Repeater::make('amenities')->label('Amenities')
                        ->simple(
                            Forms\Components\Select::make('amenities')
                                ->default(0)
                                ->options(Amenities::query()->pluck('title', 'id'))

                                ->createOptionAction(
                                    fn (Action $action) => $action->modalWidth('md'),
                                )
                                ->createOptionForm([
                                    Forms\Components\TextInput::make('title'),
                                    Forms\Components\TextInput::make('isAvailable')->default(1)->hidden()

                                ])->createOptionModalHeading('Register New Amenities'),
                        )
                        ->columnSpan(12)
                        ->addActionLabel('Add more amenities')
                        ->reorderableWithDragAndDrop(false),


                ])->columns('12')->columnSpan(9),


            ])->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('#')->rowIndex(),

                Tables\Columns\ImageColumn::make('image')->label('Img')
                    ->extraImgAttributes(['loading' => 'lazy'])
                    ->alignCenter()

                    ->default(url('images/placeholder.jpg')),

                Tables\Columns\TextColumn::make('roomNumber')->label('Room')
                    ->searchable(),

                Tables\Columns\TextColumn::make('bedCount')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('bedType.title')
                    ->searchable(['title'])
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('maxOccupancy')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->alignCenter()
                    ->default('-'),

                Tables\Columns\TextColumn::make('pricePerNight')->label('Rate per night')
                    ->money('PHP')
                    ->alignEnd()
                    ->sortable(),

                Tables\Columns\IconColumn::make('availability')
                    ->alignCenter()
                    ->boolean(),

                Tables\Columns\IconColumn::make('isSmokingAllowed')->label('Smoking Allowed')
                    ->alignCenter()
                    ->hidden()
                    ->boolean(),

                Tables\Columns\IconColumn::make('hasBalcony')
                    ->hidden()
                    ->alignCenter()
                    ->boolean(),

                Tables\Columns\IconColumn::make('isAirconditioned')
                    ->alignCenter()
                    ->boolean(),

                Tables\Columns\TextColumn::make('roomSize')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),

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
                Tables\Filters\SelectFilter::make('bedType')
                    ->relationship('bedType', 'title')
                    ->searchable()
                    ->preload(),

                Tables\Filters\Filter::make('availability')->label('Available Rooms')
                    ->query(fn (Builder $query): Builder => $query->where('availability', true)),

                Tables\Filters\Filter::make('hasBalcony')->label('With Balcony')
                    ->query(fn (Builder $query): Builder => $query->where('hasBalcony', true)),

                Tables\Filters\Filter::make('isSmokingAllowed')->label('Allowed Smoking')
                     ->query(fn (Builder $query): Builder => $query->where('isSmokingAllowed', true)),

                Tables\Filters\Filter::make('isAirconditioned')->label('Has A/C')
                    ->query(fn (Builder $query): Builder => $query->where('isAirconditioned', true)),


            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->mutateRecordDataUsing(function (array $data): array {
                    $data['last_edited_by_id'] = auth()->id();
                    return $data;
                }),
                Tables\Actions\DeleteAction::make()
                    ->modalHeading('Delete Record')
                    ->closeModalByClickingAway(false)
                    ->modalDescription('Are you sure you want to delete the record? This will not undo the action.'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->groups([
                Group::make('bedType.title')->collapsible(),



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
            'index' => Pages\ListAccommodations::route('/'),
            'create' => Pages\CreateAccommodation::route('/create'),
            'edit' => Pages\EditAccommodation::route('/{record}/edit'),

        ];
    }


}
