<?php

namespace App\Filament\Resources\HotelBookingSectionResource\Pages;

use App\Filament\Resources\HotelBookingSectionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHotelBookingSection extends EditRecord
{
    protected static string $resource = HotelBookingSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
