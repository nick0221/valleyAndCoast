<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IssuedTransactionResource\Pages;
use App\Filament\Resources\IssuedTransactionResource\RelationManagers;
use App\Models\IssuedTransaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class IssuedTransactionResource extends Resource
{
    protected static ?string $model = IssuedTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationGroup = 'Manage Inventories';

    protected static ?string $modelLabel = 'Issue new item';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('tranReference')
                    ->maxLength(255),
                Forms\Components\TextInput::make('notes')
                    ->maxLength(255),
                Forms\Components\TextInput::make('tranStatus')
                    ->numeric()
                    ->default(1),
                Forms\Components\TextInput::make('issuedBy')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tranReference')
                    ->searchable(),
                Tables\Columns\TextColumn::make('notes')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tranStatus')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('issuedBy')
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
            'index' => Pages\ListIssuedTransactions::route('/'),
            'create' => Pages\CreateIssuedTransaction::route('/create'),
            'edit' => Pages\EditIssuedTransaction::route('/{record}/edit'),
        ];
    }
}
