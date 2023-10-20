<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $recordTitleAttribute = 'fullname';

    protected static int $globalSearchResultsLimit = 10;

    protected static ?string $label = 'Guest';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Guest Information')->schema([

                    Forms\Components\TextInput::make('firstname')
                        ->columnSpan(4)
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('lastname')
                        ->columnSpan(4)
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Radio::make('gender')
                        ->inlineLabel()
                        ->columnStart(10)
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
                        ->columnSpanFull(),

                ])->columns(12),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('#')->rowIndex()
                    ->searchable(),
                Tables\Columns\TextColumn::make('fullname')->label('Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gender')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contact')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
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
                Tables\Filters\SelectFilter::make('Gender')
                    ->options([
                        'male' => 'Male',
                        'female' => 'Female',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ReservationsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }



    public static function getNavigationBadge(): ?string
    {
        $countCust = static::getModel()::count();
        return ($countCust == 0 ) ? null: $countCust;
    }


}
