<?php

namespace App\Filament\Resources\AboutResortResource\Pages;

use App\Filament\Resources\AboutResortResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAboutResorts extends ListRecords
{
    protected static string $resource = AboutResortResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
