<?php

namespace App\Filament\Resources\FourBedRoomAboutResource\Pages;

use App\Filament\Resources\FourBedRoomAboutResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFourBedRoomAbouts extends ListRecords
{
    protected static string $resource = FourBedRoomAboutResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
