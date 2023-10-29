<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerReservationResource\Pages;
use App\Filament\Resources\CustomerReservationResource\RelationManagers;
use App\Models\BedType;
use App\Models\CustomerReservation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerReservationResource extends Resource
{
    protected static ?string $model = CustomerReservation::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationGroup = 'Customer Inquiries';

    protected static ?string $navigationLabel = 'For Bookings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('accommodation_id')
                    ->relationship('accommodation', 'roomNumber')
                    ->required(),
                Forms\Components\TextInput::make('customerName')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('check_in')
                    ->required(),
                Forms\Components\DatePicker::make('check_out')
                    ->required(),
                Forms\Components\TextInput::make('contact')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('status')
                    ->maxLength(255)
                    ->default(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'Desc')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->label('Date')
                    ->dateTime('M d, Y - h:iA')
                    ->searchable(),

                Tables\Columns\TextColumn::make('accommodation.roomNumber'),

                Tables\Columns\TextColumn::make('accommodation.bed_type_id')
                    ->label('Bed Type')
                    ->formatStateUsing(fn(CustomerReservation $record) => $record->bedTypeTitle($record->id)),

                Tables\Columns\TextColumn::make('customerName')
                    ->searchable(),
                Tables\Columns\TextColumn::make('check_in')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('check_out')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('contact')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        '1' => 'Reserved',
                        '0' => 'Not Show',

                    })
                    ->color(function ($state){
                        $color = '';
                        if ($state === 0){
                            $color = 'danger';

                        }else{
                            $color = 'success';
                        }
                        return  $color;
                    })
                    ->alignCenter()
                    ->searchable(),


            ])
            ->filters([
                //
            ])
            ->actions([
                //Tables\Actions\Action::make('accept')->label('Accept'),
            ])
            ->bulkActions([
//                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
//                ]),
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
            'index' => Pages\ListCustomerReservations::route('/'),
            'create' => Pages\CreateCustomerReservation::route('/create'),
            //'edit' => Pages\EditCustomerReservation::route('/{record}/edit'),
        ];
    }



    public static function getNavigationBadge(): ?string
    {
        $countCust = static::getModel()::whereDate('created_at', now()->toDate())->count();
        return ($countCust == 0 ) ? null: $countCust;
    }







}
