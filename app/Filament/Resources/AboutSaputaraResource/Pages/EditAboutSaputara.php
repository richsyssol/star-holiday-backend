<?php

namespace App\Filament\Resources\AboutSaputaraResource\Pages;

use App\Filament\Resources\AboutSaputaraResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAboutSaputara extends EditRecord
{
    protected static string $resource = AboutSaputaraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}