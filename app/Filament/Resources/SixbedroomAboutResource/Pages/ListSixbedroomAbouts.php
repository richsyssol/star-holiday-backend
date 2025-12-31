<?php

namespace App\Filament\Resources\SixbedroomAboutResource\Pages;

use App\Filament\Resources\SixbedroomAboutResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSixbedroomAbouts extends ListRecords
{
    protected static string $resource = SixbedroomAboutResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
