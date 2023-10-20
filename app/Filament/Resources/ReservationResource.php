<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages\CreateCustomer;
use App\Filament\Resources\ReservationResource\Pages;
use App\Filament\Resources\ReservationResource\RelationManagers;
use App\Filament\Resources\ReservationResource\Widgets\ReservationOverview;
use App\Models\Accommodation;
use App\Models\AdditionalCharges;
use App\Models\BedType;
use App\Models\PaymentDetails;
use App\Models\Reservation;
use App\Models\ReservationAccommodation;
use Carbon\Carbon;
use Coolsam\FilamentFlatpickr\Forms\Components\Flatpickr;
use Filament\Actions\StaticAction;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;

use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Enums\FontWeight;
use Filament\Support\RawJs;
use Filament\Tables;

use Filament\Tables\Actions\Modal\Actions\Action;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Http\Request;
use Malzariey\FilamentDaterangepickerFilter\Fields\DateRangePicker;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;



class ReservationResource extends Resource
{
    protected static ?string $model = Reservation::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $recordTitleAttribute = 'tranReference';
    protected static int $globalSearchResultsLimit = 10;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\Select::make('customer_id')->label('Guest name')
                        ->columnSpan(4)
                        ->relationship('customer', 'fullname')
                        ->searchable(['firstname', 'lastname'])
                        ->preload()
                        ->required()
                        ->createOptionForm([
                            Forms\Components\TextInput::make('firstname')
                                ->columnSpan(3)
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('lastname')
                                ->columnSpan(3)
                                ->required()
                                ->maxLength(255),
                            Forms\Components\Radio::make('gender')->inline()
                                ->columnSpan(3)
                                ->options([
                                    'male' => 'Male',
                                    'female' => 'Female',
                                ])
                                ->required(),
                            Forms\Components\TextInput::make('contact')
                                ->columnSpan(3)
                                ->required()
                                ->maxLength(255),

                            Forms\Components\TextInput::make('email')
                                ->columnSpan(3)
                                ->email()
                                ->maxLength(255),

                            Forms\Components\Textarea::make('address')
                                ->columnSpan(3),



                        ])
                        ->createOptionAction(
                            fn (\Filament\Forms\Components\Actions\Action $action) => $action
                                ->modalWidth('md')
                                ->modalFooterActionsAlignment('end')
                                ->icon('heroicon-o-user-plus')
                                ->tooltip('Register new Guest')
                                ->modalSubmitActionLabel('Save new guest')
                                ->sendSuccessNotification() ,

                        )
                        ->createOptionModalHeading('Register New Guest'),

                    Forms\Components\DatePicker::make('checkIn')->label('Check in Date')
                        ->default(now())
                        ->columnStart(7)
                        ->prefixIcon('heroicon-o-calendar')
                        ->columnSpan(2)
                        ->closeOnDateSelection()
                        ->native(false)
                        ->weekStartsOnMonday()
                        ->required(),

                    Forms\Components\TimePicker::make('checkInTime')
                        ->timezone('Asia/Manila')
                        ->default(now())
                        ->required()
                        ->seconds(false)
                        ->columnSpan(2),

                    Forms\Components\DatePicker::make('checkOut')
                        ->prefixIcon('heroicon-o-calendar')
                        ->placeholder('-')
                        ->columnSpan(2)
                        ->date()
                        ->closeOnDateSelection()
                        ->after('checkIn')
                        ->native(false)
                        ->minDate(now())
                        ->weekStartsOnMonday()
                        ->displayFormat('M d, Y')
                        ->required(),

                    Forms\Components\TimePicker::make('checkOutTime')
                        ->columnStart(11)
                        ->timezone('Asia/Manila')
                        ->default(now())
                        ->hiddenOn('create')
                        ->required()
                        ->seconds(false)
                        ->columnSpan(2),


                    Forms\Components\Repeater::make('reservationAccommodation')->label('Accommodation')->relationship('reservationAccommodation')->schema([
                        Forms\Components\Select::make('accommodation_id')->label('Room')
                            ->relationship(
                                'accommodation',
                                'title',
                                fn (Builder $query) => $query->with('bedType')->where('availability', true),
                            )
                            ->searchable(['roomNumber'])
                            ->noSearchResultsMessage('No room accommodations available.')
                            ->preload()
                            ->required()
                            ->columnSpan(2)
                            ->getOptionLabelFromRecordUsing(fn (Model $record) => "Room: {$record->roomNumber}")
                            ->afterStateUpdated(function (Set $set, $state){
                                $accom = Accommodation::find($state);
                                $bedSize = BedType::find($accom?->bed_type_id);

                                $set('bedtype', $bedSize?->title ?? '-');
                                $set('accommodationPrice', $accom?->pricePerNight ?? 0);
                                $set('hasBalcony', $accom?->hasBalcony ?? 0);
                                $set('isSmokingAllowed', $accom?->isSmokingAllowed ?? 0);
                                $set('isAirconditioned', $accom?->isAirconditioned ?? 0);
                                $set('withBreakfast', true);

                            })
                            ->reactive()
                            ->allowHtml(),

                        Forms\Components\TextInput::make('bedtype')->label('Bed Type')
                            ->readOnly(),

                        Forms\Components\TextInput::make('accommodationPrice')->label('Rate per Night')
                            ->columnStart(5)
                            ->numeric('2', '.', ',')
                            ->readOnly(),


                        Forms\Components\Toggle::make('hasBalcony')
                            ->columnStart(1)
                            ->inline(false)
                            ->onColor('success')
                            ->onIcon('heroicon-m-check')
                            ->offIcon('heroicon-m-x-mark')
                            ->disabled(),


                        Forms\Components\Toggle::make('isAirconditioned')
                            ->inline(false)
                            ->onColor('success')
                            ->onIcon('heroicon-m-check')
                            ->offIcon('heroicon-m-x-mark')
                            ->disabled(),

                        Forms\Components\Toggle::make('isSmokingAllowed')
                            ->inline(false)
                            ->onColor('success')

                            ->onIcon('heroicon-m-check')
                            ->offIcon('heroicon-m-x-mark')
                            ->disabled(),

                        Forms\Components\Toggle::make('withBreakfast')
                            ->inline(false)
                            ->onColor('success')
                            ->offColor('danger')
                            ->onIcon('heroicon-m-check')
                            ->offIcon('heroicon-m-x-mark'),

                    ])->addActionLabel('Add more')->columns(6)->columnSpanFull()
                        ->reorderableWithDragAndDrop(false)
                        ->deletable(false)
                        ->maxItems(1),

                ])->columns(12)->columnSpan(12),

                Forms\Components\Section::make('Additional Guest')
                    ->description('Add more occupants max of 8')
                    ->schema([
                        Forms\Components\Repeater::make('guest')->hiddenLabel()
                            ->schema([
                                Forms\Components\TextInput::make('guestName')->label('Guest Name')
                                    ->live(onBlur: true)
                                    ->inlineLabel()

                            ])->reorderableWithDragAndDrop(false)->maxItems(8)
                            ->addActionLabel('Add more')
                            ->itemLabel(fn (array $state): ?string => strtoupper($state['guestName']) ?? null),
                    ])->aside()->columnSpan(12),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'DESC')
            ->columns([
                Tables\Columns\TextColumn::make('index')->label('#')
                    ->rowIndex(),

                Tables\Columns\TextColumn::make('created_at')->label('Date')
                    ->date(),

                Tables\Columns\TextColumn::make('tranReference')->label('Booking Ref')
                    ->searchable(),

                Tables\Columns\TextColumn::make('customer.fullname')->label('Guest')
                    ->searchable(['firstname', 'lastname']),

                Tables\Columns\TextColumn::make('checkIn')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('checkOut')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->date()
                    ->sortable(),

                IconColumn::make('reservationAccommodation.withBreakfast')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->placeholder('-')
                    ->label('With Breakfast')
                    ->alignCenter()
                    ->color(fn (string $state): string => match ($state) {
                        '1' => 'success',
                        default => 'gray',

                    })
                    ->icon(fn (string $state): string => match ($state) {
                        '1' => 'heroicon-o-check-circle',
                        default => 'heroicon-o-minus-small',
                    }),


                Tables\Columns\TextColumn::make('calculatedDays')->label('Length of Stay')->alignCenter()
                    ->tooltip(fn (Model $record): string => "Checkin date: ".Carbon::create($record->checkIn)->toFormattedDateString()." - ".Carbon::create($record->checkOut)->toFormattedDateString())
                    ->state(function (Model $record): string {
                        $checkIn = $record->checkIn;
                        $checkOut = $record->checkOut;
                        $diffInDays = $checkIn->diffInDays($checkOut)+1;
                        $calDays = ($diffInDays <= 1) ? "{$diffInDays}D" : "{$diffInDays}D  ".($diffInDays-1)."N";
                        return  $calDays;
                    }),


                Tables\Columns\TextColumn::make('createdBy.name')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->default('-')
                    ->sortable(),

                Tables\Columns\TextColumn::make('lastEditedBy.name')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->default('-')
                    ->sortable(),

                Tables\Columns\TextColumn::make('reservationAccommodation.accommodation.roomNumber')
                    ->label('Room')
                    ->searchable()
                    ->default('-'),

                Tables\Columns\TextColumn::make('paymentStatus')->label('Payment Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Pending Payment' => 'warning',
                        'Settled' => 'success',
                        'Voided' => 'danger',
                    })
                    ->searchable(),

                Tables\Columns\TextColumn::make('status')->label('Booking Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Reserved' => 'warning',
                        'Confirmed' => 'success',
                        'Cancelled' => 'danger',
                    })
                    ->searchable(),


                Tables\Columns\TextColumn::make('status')->label('Booking Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Reserved' => 'warning',
                        'Confirmed' => 'success',
                        'Cancelled' => 'danger',

                    })
                    ->searchable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->label('Booking Status')
                    ->options([
                        'Reserved' => 'Reserved',
                        'Confirmed' => 'Confirmed',
                        'Cancelled' => 'Cancelled',
                    ]),


                Tables\Filters\SelectFilter::make('paymentStatus')
                    ->options([
                        'Pending Payment' => 'Pending Payment',
                        'Settled' => 'Settled',
                        'Voided' => 'Voided',
                    ]),

                DateRangeFilter::make('checkIn')
                    ->withIndicator()
                    ->timezone('UTC')
                    ->setAutoApplyOption(true),




            ])
            ->actions(actions: [
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('PrintInvoice')
                        ->color('info')
                        ->hidden(
                            function (Reservation $record){
                                return ($record->paymentStatus !== 'Settled');
                            }
                        )
                        ->url(fn (Reservation $record): string => route('print.invoice', $record))
                        ->openUrlInNewTab()
                        ->outlined()
                        ->icon('heroicon-o-printer'),
                    Tables\Actions\ViewAction::make()->label('View more details'),

                    Tables\Actions\Action::make('addCharges')
                        ->form([
                            Forms\Components\Repeater::make('addCharges')->hiddenLabel()->schema([
                                TextInput::make('chargeFor')->label('Particulars')
                                    ->required(),

                                TextInput::make('chargePrice')->label('Amount')
                                    ->numeric('2', '.', ',')
                                    ->required(),

                            ])->columns(2)->reorderableWithDragAndDrop(false)->addActionLabel('Add more items'),

                        ])
                        ->action(function (array $data, Reservation $record): void {
                            $record->addChargesToGuest($record, $data);

                        })
                        ->stickyModalFooter()
                        ->stickyModalHeader()
                        ->closeModalByClickingAway(false)
                        ->modalHeading('Add charges to Guest')
                        ->hidden(function (Reservation $record){
                            if ($record->status === "Cancelled" ){
                                return true;
                            }if ($record->paymentStatus === "Settled" ){
                                return true;
                            }else{
                                return false;

                            }
                        })
                        ->icon('heroicon-m-squares-plus')
                        ->label('Add Charges to Guest')
                        ->color('info'),



                    Tables\Actions\Action::make('checkout')->label('Checkout')
                        ->hidden(function (Reservation $record){
                            if ($record->status === "Cancelled" ){
                                return true;
                            }if ($record->paymentStatus === "Settled" ){
                                return true;
                            }else{
                                return false;

                            }
                        })
                        ->color('success')
                        ->fillForm(fn (Reservation $record): array =>
//                        dd($record->reservationAccommodation[0]->accommodation_id)
                        [

                            'fullname' => $record->customer->fullname,
                            'checkOut' => $record->checkOut,
                            'payMethod' => $record->paymentDetails?->payMethod,
                            'cardNumber' => $record->paymentDetails?->cardNumber,
                            'cardType' => $record->paymentDetails?->cardType,
                            'cardHolder' => $record->paymentDetails?->cardHolder,
                            'notes' => $record->paymentDetails?->notes,
                            'checkOutTime' => now(),
                            'bedType' => $record->reservationAccommodation[0]->bedtype,
                            'totalCharge' => $record->reservationAccommodation[0]->accommodationPrice,
                            'withBreakfast' => ($record->reservationAccommodation[0]->withBreakfast) ? "Yes" : "No",
                            'roomNumber' => Accommodation::where('id', $record->reservationAccommodation[0]->accommodation_id)->first()->roomNumber,

                        ]
                         )
                        ->form([
                            Forms\Components\Section::make('Guest Details')->description('Make sure to double check the guest details.')->schema([
                                TextInput::make('fullname')->label('Name')->disabled(),
                                TextInput::make('roomNumber')->disabled(),
                                TextInput::make('bedType')->disabled(),
                                TextInput::make('withBreakfast')->disabled(),

                            ])->columns(2)->aside()->compact(),

                            Forms\Components\Section::make('Checkout date & time')->description('Please fill out the required fields')->schema([
                                Forms\Components\DatePicker::make('checkOut')->label('Date')
                                    ->prefixIcon('heroicon-o-calendar')
                                    ->readOnly()
                                    ->date()
//                                    ->closeOnDateSelection()
                                    ->after('checkIn')
                                    ->minDate(now()->format('M d, Y'))
                                    ->default(now())
                                    ->weekStartsOnMonday()
                                    ->displayFormat('M d, Y')
                                    ->required(),

                                Forms\Components\TimePicker::make('checkOutTime')->label('Time')
                                    ->timezone('Asia/Manila')
                                    ->default(now())
                                    ->required()
                                    ->seconds(false)



                            ])->columns(2)->aside()->compact(),

                            Forms\Components\Section::make('Additional Charges')
                                ->hidden(function (Reservation $record){
                                    return $record->guestChargesDue($record) == 0;
                                })
                                ->schema([
                                    Forms\Components\Repeater::make('addCharges')->hiddenLabel()
                                        ->relationship()
                                        ->schema([
                                            TextInput::make('chargeFor')->disabled()->label('Particular'),
                                            TextInput::make('chargePrice')->disabled()
                                        ])->addable(false)->deletable(false)->columns(2)
                                ])->aside()->compact()->columnSpanFull(),




                            Forms\Components\Section::make('Payment Details')->description('Please double check the Payment details before submitting the form.')->schema([
                                Forms\Components\Select::make('payMethod')->label('Method')
                                    ->options([
                                        'Cash' => 'Cash',
                                        'Card' => 'Card',
                                        'Gcash' => 'Gcash',
                                    ])
                                    ->required()
                                    ->columnSpan(2)
                                    ->live(),

                                TextInput::make('cardNumber')
                                    ->columnSpan(1)
                                    ->required()
                                    ->hidden(fn (Get $get) => $get('payMethod') !== 'Card')
                                    ->placeholder('**** **** **** ****')
                                    ->autocomplete(false)
                                    ->mask(RawJs::make(<<<'JS'
                                        $input.startsWith('34') || $input.startsWith('37') ? '9999 999999 99999' : '9999 9999 9999 9999'
                                    JS)),

                                Forms\Components\Select::make('cardType')->label('Card Type')
                                    ->options([
                                        'Visa' => 'Visa',
                                        'MasterCard' => 'MasterCard',
                                        'American Express' => 'American Express',
                                        'Discover' => 'Discover',
                                        'JCB (Japan Credit Bureau)' => 'JCB (Japan Credit Bureau)',
                                        'Diners Club' => 'Diners Club',
                                        'UnionPay' => 'UnionPay',
                                        'Maestro' => 'Maestro',
                                    ])
                                    ->columnSpan(1)
                                    ->required()
                                    ->hidden(fn (Get $get) => $get('payMethod') !== 'Card'),

                                TextInput::make('cardHolder')->label('Name on the Card')
                                    ->columnSpan(2)
                                    ->autocapitalize()
                                    ->required()
                                    ->hidden(fn (Get $get) => $get('payMethod') !== 'Card')
                                    ->autocomplete(false),


                                Forms\Components\Textarea::make('notes')->label('Remarks/Notes')
                                    ->columnSpan(2)
                                    ->columnSpanFull()
                                    ->hidden(fn (Get $get) => $get('payMethod') !== 'Card')
                                    ->autocomplete(false),


                                TextInput::make(__('ttlAddCharges')) ->label('Additional Charge')
                                    ->prefix('₱')
                                    ->default(0)
                                    ->formatStateUsing(function (Reservation $record){
                                        return $record->guestChargesDue($record);
                                    })
                                    ->readOnly()
                                    ->numeric('2', '.', ',')
                                    ->columnSpan(2),

                                TextInput::make(__('totalCharge')) ->label('Room Accommodation')
                                    ->prefix('₱')
                                    ->default(0)
                                    ->readOnly()
                                    ->numeric('2', '.', ',')
                                    ->columnSpan(2),


                                TextInput::make(__('overAlltotalCharge')) ->label('Total Amount Due ')
                                    ->hint('Note: Additional charges included (if available)')
                                    ->prefix('₱')
                                    ->default(0)
                                    ->formatStateUsing(function (Reservation $record){
                                        return $record->guestAmtDue($record);
                                    })
                                    ->readOnly()
                                    ->numeric('2', '.', ',')
                                    ->columnSpan(2),


                            ])->columns(2)->aside()->compact(),

                        ])
                        ->closeModalByClickingAway(false)
                        ->modalSubmitActionLabel('Checkout Guest')
                        ->modalHeading('Checkout Form')
                        ->action(function (array $data, Reservation $record): void {
                            $record->checkOut = $data['checkOut'];
                            $record->checkOutTime = $data['checkOutTime'];
                            $record->paymentStatus = 'Settled';
                            $record->save();
                            $record->notifySuccessCheckout($record);
                            $record->paymentDetails()->updateOrInsert(
                                ['reservation_id' => $record->id],
                                [
                                    'payMethod' => $data['payMethod'],
                                    'totalCharge' => $data['totalCharge'],
                                    'notes' => (!empty($data['notes'])) ? $data['notes']:null,
                                    'cardHolder' => (!empty($data['cardHolder'])) ? $data['cardHolder']:null,
                                    'cardNumber' => (!empty($data['cardNumber'])) ? $data['cardNumber']:null,
                                    'cardType' => (!empty($data['cardType'])) ? $data['cardType']:null,

                                ]
                            );
                            $record->setRoomToAvailable($record->getAccommodation[0]->accommodation_id);
                        })
                        ->modalWidth('4xl')
                        ->icon('heroicon-o-clipboard-document-check')
                        ->hidden(function (Reservation $record){
                            if ($record->status === "Cancelled"){
                                return true;
                            }else{
                                return false;

                            }
                        })->stickyModalHeader()->stickyModalFooter()
                        ->hidden(function (Reservation $record){
                            if ($record->status === "Cancelled" ){
                                return true;
                            }if ($record->paymentStatus === "Settled" ){
                                return true;
                            }else{
                                return false;

                            }
                        }),






                    Tables\Actions\Action::make('cancel')->label('Cancel Booking')
                        ->hidden(function (Reservation $record){
                            if ($record->status === "Cancelled" ){
                                return true;
                            }if ($record->paymentStatus === "Settled" ){
                                return true;
                            }else{
                                return false;

                            }
                        })
                        ->modalHeading('Cancel Reservation')
                        ->color('danger')
                        ->icon('heroicon-o-archive-box-x-mark')
                        ->requiresConfirmation()
                        ->closeModalByClickingAway(false)
                        ->modalSubmitActionLabel('Yes, cancel it')
                        ->modalCancelActionLabel('No')
                        ->action(function (Reservation $record){
                            $record->status = "Cancelled";
                            $record->paymentStatus = "Voided";
                            $record->setAmountZero($record);
                            $record->save();
                            $record->setRoomToAvailable($record->getAccommodation[0]->accommodation_id);
                            $record->sendSuccessCancelBooking($record);
                        })->stickyModalHeader(),




                ])
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->color('primary')
                    ->tooltip('See Actions')

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
                Section::make('Booking Information')->schema([
                    TextEntry::make('created_at')->label('Transaction Date:')

                        ->size(TextEntry\TextEntrySize::Medium)
                        ->timezone('Asia/Manila')
                        ->date('M d, Y - H:i A')
                        ->columnSpan(5),

                    Fieldset::make('Checkin Date&Time')->schema([
                        TextEntry::make('checkIn')->inlineLabel()->hiddenLabel()
                            ->date()
                            ->weight(FontWeight::Bold)
                            ->size(TextEntry\TextEntrySize::Large)
                            ->alignCenter(),

                        TextEntry::make('checkInTime')->inlineLabel()->hiddenLabel()
                            ->placeholder('-- : --')
                            ->timezone('Asia/Manila')
                            ->weight(FontWeight::Bold)
                            ->size(TextEntry\TextEntrySize::Large)
                            ->time('h:i A')
                            ->alignStart(),
                    ])->columnStart(7)->columnSpan(6),

                    Fieldset::make('CheckOut Date&Time')->schema([
                        TextEntry::make('checkOut')->inlineLabel()->hiddenLabel()
                            ->date()
                            ->size(TextEntry\TextEntrySize::Large)
                            ->weight(FontWeight::Bold)
                            ->alignCenter(),

                        TextEntry::make('checkOutTime')->label('Time:')->inlineLabel()->hiddenLabel()
                            ->placeholder('-- : --')
                            ->timezone('Asia/Manila')
                            ->weight(FontWeight::Bold)
                            ->size(TextEntry\TextEntrySize::Large)
                            ->time('h:i A')
                            ->alignStart(),
                    ])->columnStart(7)->columnSpan(6),

                    TextEntry::make('tranReference')->label('Booking Reference')
                        ->formatStateUsing(fn (string $state): string => strtoupper($state))
                        ->size(TextEntry\TextEntrySize::Large)
                        ->weight(FontWeight::Bold)
                        ->columnSpan(4)
                        ->columnStart(1)
                        ->color('info'),

                    TextEntry::make('status')->label('Booking Status:')
                        ->formatStateUsing(fn (string $state): string => strtoupper($state))
                        ->size(TextEntry\TextEntrySize::Large)
                        ->weight(FontWeight::Bold)
                        ->color(fn (string $state): string => match ($state) {
                            'Confirmed' => 'info',
                            'Cancelled' => 'danger',
                            'Reserved' => 'warning',
                        })
                        ->columnSpan(4),

                    TextEntry::make('Length of Stay')
                        ->state(function (Model $record): string {
                            $checkIn = $record->checkIn;
                            $checkOut = $record->checkOut;
                            $diffInDays = $checkIn->diffInDays($checkOut)+1;
                            $calDays = ($diffInDays <= 1) ? "{$diffInDays}D" : "{$diffInDays}D  ".($diffInDays-1)."N";
                            return  $calDays;
                        })

                        ->size(TextEntry\TextEntrySize::Large)
                        ->weight(FontWeight::Bold)
                        ->alignCenter()
                        ->columnSpan(2),


                    TextEntry::make('customer.fullname')
                        ->columnStart(1)
                        ->size(TextEntry\TextEntrySize::Large)
                        ->weight(FontWeight::Bold)
                        ->columnSpan(12),


                    Fieldset::make('Accommodation details')->schema([
                        RepeatableEntry::make('reservationAccommodation')->hiddenLabel()->schema([

                            TextEntry::make('accommodation.roomNumber')
                                ->columnSpan(3)
                                ->label('Room No: '),

                            TextEntry::make('accommodation.bedType.title')
                                ->columnSpan(3)
                                ->label('Bed Type: '),

                            TextEntry::make('accommodation.hasBalcony')
                                ->formatStateUsing(fn (string $state): string => match ($state) {
                                    '1' => 'Yes',
                                    '0' => 'No',
                                })
                                ->columnSpan(3)
                                ->label('Has Balcony: '),

                            TextEntry::make('accommodationPrice')
                                ->numeric('2', '.', ',')
                                ->columnSpan(3)
                                ->label('Rate Per Night'),

                            TextEntry::make('accommodation.isSmokingAllowed')
                                ->formatStateUsing(fn (string $state): string => match ($state) {
                                    '1' => 'Yes',
                                    '0' => 'No',
                                })
                                ->columnSpan(3)
                                ->label('Smoking Allowed: '),

                            TextEntry::make('withBreakfast')
                                ->formatStateUsing(fn (string $state): string => match ($state) {
                                    '1' => 'Yes',
                                    '0' => 'No',
                                })
                                ->columnSpan(3)
                                ->label('With Breakfast: '),


                            TextEntry::make('totalAmtDue')
                                ->numeric('2', '.', ',')
                                ->columnSpan(3)
                                ->money('PHP')
                                ->size(TextEntry\TextEntrySize::Large)
                                ->weight(FontWeight::Bold)
                                ->label('Total Due: '),



                        ])->columnSpanFull()->columns(12)->contained(false),

                        RepeatableEntry::make('guest')->label('Additional Guest')->schema([
                            TextEntry::make('guestName')->inlineLabel()->hiddenLabel()
                        ])->contained(false),



                    ])->columnSpan(12),


                ])->columns(12),


                Section::make('Payment Information')
                    ->description('Customer\'s payment information')
                    ->schema([
                        TextEntry::make('paymentStatus')->inlineLabel()->label('Status: ')
                            ->color(fn (string $state): string => match ($state) {
                                'Voided' => 'danger',
                                'Pending Payment' => 'warning',
                                'Settled' => 'success',
                            })
                            ->size(TextEntry\TextEntrySize::Large)
                            ->weight(FontWeight::Bold)
                            ->columnSpan(1),

                        RepeatableEntry::make('paymentInformation')->hiddenLabel()->schema([
                            TextEntry::make('payMethod')
                                ->size(TextEntry\TextEntrySize::Large)
                                ->weight(FontWeight::Bold)
                                ->columnSpan(4),

                            Fieldset::make('Card Details')
                                ->hidden(
                                    function (PaymentDetails $rec){
                                        return ($rec->payMethod == "Cash" || $rec->payMethod == "Gcash");
                                    }
                                )
                                ->schema([
                                    TextEntry::make('cardNumber')->inlineLabel()->label('Card Number:')
                                        ->size(TextEntry\TextEntrySize::Large)
                                        ->weight(FontWeight::Bold)
                                        ->columnSpan(4),

                                    TextEntry::make('cardType')->inlineLabel()->label('Card Type:')
                                        ->size(TextEntry\TextEntrySize::Large)
                                        ->weight(FontWeight::Bold)
                                        ->columnSpan(4),

                                    TextEntry::make('cardHolder')->inlineLabel()->label('Card Holder:')
                                        ->size(TextEntry\TextEntrySize::Large)
                                        ->weight(FontWeight::Bold)
                                        ->columnSpan(4),





                                ])->columnSpan(8),



                        ])->contained(false)->columns(12)->columnSpan(12),
                    ])->aside(),


            ]);

    }


    public static function getRelations(): array
    {
        return [
            RelationManagers\AdditionalChargesRelationManager::class
        ];
    }



    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReservations::route('/'),
            'create' => Pages\CreateReservation::route('/create'),
            'edit' => Pages\EditReservation::route('/{record}/edit'),
            'view' => Pages\ViewReservation::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $countConfirmed = self::getModel()::where('status', 'Confirmed')->whereDate('created_at', '=', now())->count();
        return ($countConfirmed > 0) ? $countConfirmed : null;

        //return (static::getModel()::count() > 0) ? static::getModel()::count() : null;
    }


    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Customer' => $record->customer->fullname,
            'Booking Status' => $record->status,


        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['tranReference', 'customer.fullname'];
    }
    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['customer']);
    }

    public static function getGlobalSearchResultUrl(Model $record): string
    {
        return ReservationResource::getUrl('view', ['record' => $record]);
    }



}
