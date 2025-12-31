<?php

namespace App\Filament\Resources\FamilyRoomImageResource\Pages;

use App\Filament\Resources\FamilyRoomImageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFamilyRoomImages extends ListRecords
{
    protected static string $resource = FamilyRoomImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
