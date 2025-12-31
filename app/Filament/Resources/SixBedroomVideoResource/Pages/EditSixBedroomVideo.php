<?php

namespace App\Filament\Resources\SixBedroomVideoResource\Pages;

use App\Filament\Resources\SixBedroomVideoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSixBedroomVideo extends EditRecord
{
    protected static string $resource = SixBedroomVideoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
