<?php

namespace App\Filament\Resources\FestivalGalleryResource\Pages;

use App\Filament\Resources\FestivalGalleryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFestivalGallery extends EditRecord
{
    protected static string $resource = FestivalGalleryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
