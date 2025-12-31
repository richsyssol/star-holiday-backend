<?php

namespace App\Filament\Resources\SixbedroomAboutResource\Pages;

use App\Filament\Resources\SixbedroomAboutResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSixbedroomAbout extends EditRecord
{
    protected static string $resource = SixbedroomAboutResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
