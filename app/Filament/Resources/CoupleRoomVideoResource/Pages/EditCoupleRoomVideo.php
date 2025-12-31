<?php

namespace App\Filament\Resources\CoupleRoomVideoResource\Pages;

use App\Filament\Resources\CoupleRoomVideoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCoupleRoomVideo extends EditRecord
{
    protected static string $resource = CoupleRoomVideoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
