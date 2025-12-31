<?php

namespace App\Filament\Resources\PhotoImageResource\Pages;

use App\Filament\Resources\PhotoImageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPhotoImages extends ListRecords
{
    protected static string $resource = PhotoImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
