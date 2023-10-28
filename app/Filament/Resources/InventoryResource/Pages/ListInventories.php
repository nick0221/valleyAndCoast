<?php

namespace App\Filament\Resources\InventoryResource\Pages;

use App\Filament\Resources\InventoryResource;
use App\Models\Inventory;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
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
                            Column::make('remainingStocks')
                                ->heading('Remaining Stocks')
                                ->formatStateUsing(fn ($state):int => ($state == 0 || !is_int($state)) ? 0 : $state),


                        ])
                        ->except('updated_at' , 'index')
                        ->withFilename(fn () => 'InventoryReport as of '.now()->toDateString())
                ])
        ];
    }



    public function getTabs(): array
    {
        $lowStockThreshold = env('LOW_STOCK_THRESHOLD', 5);
        $outOfStock = Inventory::query()->where('remainingStocks', '<=', 0)->count();
        $lowSupplies = Inventory::query()->whereBetween('remainingStocks', [1, $lowStockThreshold])->count();

        return [
            'all' => Tab::make('All'),

            'lowSupplies' => Tab::make('Low Supplies')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereBetween('remainingStocks', [1, $lowStockThreshold]))
                ->badge(($lowSupplies === 0) ? '':$lowSupplies)
                ->badgeColor('danger'),

            'outOfStock' => Tab::make('Out of Stock')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('remainingStocks', '<=', 0))
                ->badge(($outOfStock === 0) ? '':$outOfStock)
                ->badgeColor('danger'),




        ];
    }









}
