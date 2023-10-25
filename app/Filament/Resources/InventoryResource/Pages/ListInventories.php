<?php

namespace App\Filament\Resources\InventoryResource\Pages;

use App\Filament\Resources\InventoryResource;
use App\Models\Inventory;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

use Illuminate\Database\Eloquent\Builder;
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
//                            Column::make('id')
//                                ->heading('Received Stocks')
//                                ->formatStateUsing(fn (Inventory $record):int =>  $record->countReceived($record->id)),


                        ])
                        ->except('updated_at' , 'index')
                        ->withFilename(fn () => 'InventoryRecords-'.now()->toDateString())
                ])
        ];
    }






    public function getTabs(): array
    {
        $lowStockThreshold = env('LOW_STOCK_THRESHOLD', 5);

        return [
            'all' => Tab::make('All'),

            'lowSupplies' => Tab::make('Low Supplies')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereBetween('remainingStocks', [1, $lowStockThreshold]))
                ->badge(Inventory::query()->whereBetween('remainingStocks', [1, $lowStockThreshold])->count())
                ->badgeColor('danger'),

            'outOfStock' => Tab::make('Out of Stock')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('remainingStocks', '<=', 0))
                ->badge(Inventory::query()->where('remainingStocks', '<=', 0)->count())
                ->badgeColor('danger'),


        ];
    }









}
