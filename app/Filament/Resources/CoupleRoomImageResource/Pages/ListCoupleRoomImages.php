<?php

namespace App\Filament\Resources\CoupleRoomImageResource\Pages;

use App\Filament\Resources\CoupleRoomImageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCoupleRoomImages extends ListRecords
{
    protected static string $resource = CoupleRoomImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
