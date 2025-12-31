<?php

namespace App\Filament\Resources\CoupleRoomVideoResource\Pages;

use App\Filament\Resources\CoupleRoomVideoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCoupleRoomVideos extends ListRecords
{
    protected static string $resource = CoupleRoomVideoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
