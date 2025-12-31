<?php

namespace App\Filament\Resources\AboutFacilityContentResource\Pages;

use App\Filament\Resources\AboutFacilityContentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAboutFacilityContents extends ListRecords
{
    protected static string $resource = AboutFacilityContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
