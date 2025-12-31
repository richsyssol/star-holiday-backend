<?php

namespace App\Filament\Resources\VideoFeedbackResource\Pages;

use App\Filament\Resources\VideoFeedbackResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVideoFeedbacks extends ListRecords
{
    protected static string $resource = VideoFeedbackResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Disable create action since videos come from frontend only
            // Actions\CreateAction::make(),
        ];
    }
}