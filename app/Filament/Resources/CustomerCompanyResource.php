<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerCompanyResource\Pages;
use App\Filament\Resources\CustomerCompanyResource\RelationManagers;
use App\Models\CustomerCompany;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerCompanyResource extends Resource
{
    protected static ?string $model = CustomerCompany::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $navigationLabel = 'Companies';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('businessType')
                    ->maxLength(255),
                Forms\Components\TextInput::make('birNum')
                    ->maxLength(255),
                Forms\Components\Textarea::make('address'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('businessType')
                    ->searchable(),
                Tables\Columns\TextColumn::make('birNum')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
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
            'index' => Pages\ListCustomerCompanies::route('/'),
            'create' => Pages\CreateCustomerCompany::route('/create'),
            'edit' => Pages\EditCustomerCompany::route('/{record}/edit'),
        ];
    }
}
