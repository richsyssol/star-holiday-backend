<?php

namespace App\Filament\Resources\VideoFeedbackResource\Pages;

use App\Filament\Resources\VideoFeedbackResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVideoFeedback extends EditRecord
{
    protected static string $resource = VideoFeedbackResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
