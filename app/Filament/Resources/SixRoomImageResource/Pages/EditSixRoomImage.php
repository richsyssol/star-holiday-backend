<?php

namespace App\Filament\Resources\SixRoomImageResource\Pages;

use App\Filament\Resources\SixRoomImageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSixRoomImage extends EditRecord
{
    protected static string $resource = SixRoomImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
