<?php

namespace App\Filament\Resources\RestaurantSectionResource\Pages;

use App\Filament\Resources\RestaurantSectionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRestaurantSections extends ListRecords
{
    protected static string $resource = RestaurantSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
