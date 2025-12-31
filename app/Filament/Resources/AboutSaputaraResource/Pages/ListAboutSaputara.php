<?php

namespace App\Filament\Resources\AboutSaputaraResource\Pages;

use App\Filament\Resources\AboutSaputaraResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAboutSaputara extends ListRecords
{
    protected static string $resource = AboutSaputaraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}