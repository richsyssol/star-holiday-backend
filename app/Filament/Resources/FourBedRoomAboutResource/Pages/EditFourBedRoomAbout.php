<?php

namespace App\Filament\Resources\FourBedRoomAboutResource\Pages;

use App\Filament\Resources\FourBedRoomAboutResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFourBedRoomAbout extends EditRecord
{
    protected static string $resource = FourBedRoomAboutResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
