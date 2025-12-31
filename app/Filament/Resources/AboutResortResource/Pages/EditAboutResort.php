<?php

namespace App\Filament\Resources\AboutResortResource\Pages;

use App\Filament\Resources\AboutResortResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAboutResort extends EditRecord
{
    protected static string $resource = AboutResortResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
