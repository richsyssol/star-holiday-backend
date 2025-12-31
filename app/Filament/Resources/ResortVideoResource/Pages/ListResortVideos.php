<?php

namespace App\Filament\Resources\ResortVideoResource\Pages;

use App\Filament\Resources\ResortVideoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListResortVideos extends ListRecords
{
    protected static string $resource = ResortVideoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
