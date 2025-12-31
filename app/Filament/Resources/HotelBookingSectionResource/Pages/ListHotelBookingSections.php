<?php

namespace App\Filament\Resources\HotelBookingSectionResource\Pages;

use App\Filament\Resources\HotelBookingSectionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHotelBookingSections extends ListRecords
{
    protected static string $resource = HotelBookingSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
