<?php

namespace App\Filament\Resources\RestaurantSectionResource\Pages;

use App\Filament\Resources\RestaurantSectionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRestaurantSection extends EditRecord
{
    protected static string $resource = RestaurantSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
