<?php

namespace App\Filament\Resources\RestaurantGalleryResource\Pages;

use App\Filament\Resources\RestaurantGalleryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRestaurantGallery extends EditRecord
{
    protected static string $resource = RestaurantGalleryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
