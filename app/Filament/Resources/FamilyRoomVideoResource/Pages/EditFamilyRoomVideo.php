<?php

namespace App\Filament\Resources\FamilyRoomVideoResource\Pages;

use App\Filament\Resources\FamilyRoomVideoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFamilyRoomVideo extends EditRecord
{
    protected static string $resource = FamilyRoomVideoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
