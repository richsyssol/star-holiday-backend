<?php

namespace App\Filament\Resources\StayReasonResource\Pages;

use App\Filament\Resources\StayReasonResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStayReason extends EditRecord
{
    protected static string $resource = StayReasonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
