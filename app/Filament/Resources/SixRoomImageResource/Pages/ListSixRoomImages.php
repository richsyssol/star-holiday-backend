<?php

namespace App\Filament\Resources\SixRoomImageResource\Pages;

use App\Filament\Resources\SixRoomImageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSixRoomImages extends ListRecords
{
    protected static string $resource = SixRoomImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
