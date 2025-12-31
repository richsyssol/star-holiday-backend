<?php

namespace App\Filament\Resources\PhotoVideoResource\Pages;

use App\Filament\Resources\PhotoVideoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPhotoVideo extends EditRecord
{
    protected static string $resource = PhotoVideoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
