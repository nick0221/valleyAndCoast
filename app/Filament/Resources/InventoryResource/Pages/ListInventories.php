<?php

namespace App\Filament\Resources\InventoryResource\Pages;

use App\Filament\Resources\InventoryResource;
use App\Models\Inventory;
use App\Models\MassReceive;
use App\Models\ReceivedStock;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class ListInventories extends ListRecords
{
    protected static string $resource = InventoryResource::class;

    protected ?string $heading = 'Inventories';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Create new'),

            ExportAction::make()->color('success')
                ->exports([

                    ExcelExport::make()->fromTable()
                        ->withColumns([
                            Column::make('id')
                                ->heading('Received Stocks')
                                ->formatStateUsing(fn (Inventory $record):int =>  $record->countReceived($record->id)),


                        ])
                        ->except('updated_at' , 'index')
                        ->withFilename(fn () => 'InventoryRecords-'.now()->toDateString())
                ])
        ];
    }
}
