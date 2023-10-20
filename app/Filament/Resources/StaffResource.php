<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StaffResource\Pages;
use App\Filament\Resources\StaffResource\RelationManagers;
use App\Models\Staff;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Enums\IconSize;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class StaffResource extends Resource
{
    protected static ?string $model = Staff::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Register New Staff')
                    ->description('Note: Fill out all the required(*) fields')
                    ->icon('heroicon-o-identification')
                    ->schema([

                    Forms\Components\TextInput::make('firstname')
                        ->columnSpan(4)
                        ->required()
                        ->autocomplete(false)
                        ->maxLength(255),
                    Forms\Components\TextInput::make('lastname')
                        ->columnSpan(4)
                        ->required()
                        ->autocomplete(false)
                        ->maxLength(255),

                    Forms\Components\Radio::make('gender')
                        ->options([
                            'Male' => 'Male',
                            'Female' => 'Female',
                        ])
                        ->columnSpan(3),

                    Forms\Components\TextInput::make('contact')
                        ->columnSpan(4)
                        ->autocomplete(false)
                        ->maxLength(255),

                    Forms\Components\DatePicker::make('dateHired')
                        ->columnSpan(3),


                    Forms\Components\DatePicker::make('dateResign')
                        ->hiddenOn('create')
                        ->columnSpan(3),




                    Forms\Components\Textarea::make('address')
                        ->autocomplete(false)
                        ->columnSpanFull(),


                ])->columns(12),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'DESC')
            ->columns([

                Tables\Columns\TextColumn::make('fullname')->label('Name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('gender')
                    ->searchable(),

                Tables\Columns\TextColumn::make('contact')
                    ->searchable(),

                Tables\Columns\TextColumn::make('dateHired')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('dateResign')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->date()
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

                    ExportBulkAction::make()
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
            'index' => Pages\ListStaff::route('/'),
            'create' => Pages\CreateStaff::route('/create'),
            'edit' => Pages\EditStaff::route('/{record}/edit'),
        ];
    }






}
