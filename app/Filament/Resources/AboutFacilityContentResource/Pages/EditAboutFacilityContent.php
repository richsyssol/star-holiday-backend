<?php

namespace App\Filament\Resources\AboutFacilityContentResource\Pages;

use App\Filament\Resources\AboutFacilityContentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAboutFacilityContent extends EditRecord
{
    protected static string $resource = AboutFacilityContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
