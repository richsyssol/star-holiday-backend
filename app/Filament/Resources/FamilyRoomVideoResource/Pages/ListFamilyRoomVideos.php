<?php

namespace App\Filament\Resources\FamilyRoomVideoResource\Pages;

use App\Filament\Resources\FamilyRoomVideoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFamilyRoomVideos extends ListRecords
{
    protected static string $resource = FamilyRoomVideoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
