<?php

namespace App\Filament\Resources\StaffResource\Pages;

use App\Filament\Resources\StaffResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;


class ListStaff extends ListRecords
{
    protected static string $resource = StaffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

            ExportAction::make()->color('success')
                ->exports([

                ExcelExport::make()->fromModel()
                    ->except('updated_at', 'id')
                    ->withFilename(fn () => 'ExportedStaffRecords-'.now()->toDateString())
            ])
        ];
    }




    protected function getHeaderWidgets(): array
    {
        return [
            StaffResource\Widgets\StaffOverview::make(),
        ];
    }





}
