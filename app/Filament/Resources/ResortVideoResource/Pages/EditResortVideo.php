<?php

namespace App\Filament\Resources\ResortVideoResource\Pages;

use App\Filament\Resources\ResortVideoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditResortVideo extends EditRecord
{
    protected static string $resource = ResortVideoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
