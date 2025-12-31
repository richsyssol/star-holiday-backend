<?php

namespace App\Filament\Resources\CoupleRoomImageResource\Pages;

use App\Filament\Resources\CoupleRoomImageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCoupleRoomImage extends EditRecord
{
    protected static string $resource = CoupleRoomImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
