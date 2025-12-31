<?php

namespace App\Filament\Resources\FamilyRoomImageResource\Pages;

use App\Filament\Resources\FamilyRoomImageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFamilyRoomImage extends EditRecord
{
    protected static string $resource = FamilyRoomImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
