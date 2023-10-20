<?php

namespace App\Filament\Resources\AccommodationResource\Pages;

use App\Filament\Resources\AccommodationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class ListAccommodations extends ListRecords
{
    protected static string $resource = AccommodationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->mutateFormDataUsing(function (array $data): array {
                    $data['user_id'] = auth()->id();
                    return $data;
                }),

            ExportAction::make()->color('success')
                ->exports([

                    ExcelExport::make()->fromModel()
                        ->withColumns([
                            Column::make('isAirconditioned')
                                ->formatStateUsing(fn ($state) => ($state === 1) ? 'Yes':'No'),

                            Column::make('pricePerNight')
                                ->formatStateUsing(fn ($state) => number_format($state, 2)),

                        ])
                        ->except('create_at', 'id', 'bed_type_id', 'availability', 'isSmokingAllowed', 'hasBalcony', 'image', 'user_id', 'last_edited_by_id', 'updated_at', 'amenities')
                        ->withFilename(fn () => 'ExportedAccommodationRecords-'.now()->toDateString())
                        ->modifyQueryUsing(fn ($query) => $query->leftJoin('bed_types', 'bed_types.id', '=', 'accommodations.bed_type_id'))

                ])
        ];
    }


    protected function getHeaderWidgets(): array
    {
        return [
           AccommodationResource\Widgets\Availability::make(),
        ];
    }

}
