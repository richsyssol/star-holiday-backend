<?php

namespace App\Filament\Resources\PhotoImageResource\Pages;

use App\Filament\Resources\PhotoImageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPhotoImage extends EditRecord
{
    protected static string $resource = PhotoImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
