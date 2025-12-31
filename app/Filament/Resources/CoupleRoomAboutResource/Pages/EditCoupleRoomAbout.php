<?php

namespace App\Filament\Resources\CoupleRoomAboutResource\Pages;

use App\Filament\Resources\CoupleRoomAboutResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCoupleRoomAbout extends EditRecord
{
    protected static string $resource = CoupleRoomAboutResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
