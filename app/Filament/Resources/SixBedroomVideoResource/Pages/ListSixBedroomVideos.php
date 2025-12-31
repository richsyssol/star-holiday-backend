<?php

namespace App\Filament\Resources\SixBedroomVideoResource\Pages;

use App\Filament\Resources\SixBedroomVideoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSixBedroomVideos extends ListRecords
{
    protected static string $resource = SixBedroomVideoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
