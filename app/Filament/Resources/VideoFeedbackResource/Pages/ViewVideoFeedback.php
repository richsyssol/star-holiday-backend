<?php

namespace App\Filament\Resources\VideoFeedbackResource\Pages;

use App\Filament\Resources\VideoFeedbackResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Storage;

class ViewVideoFeedback extends ViewRecord
{
    protected static string $resource = VideoFeedbackResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('download')
                ->label('Download Video')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function () {
                    $videoPath = $this->record->video_path;
                    
                    if (!$videoPath) {
                        Notification::make()
                            ->title('No Video File')
                            ->body('No video file path found for this record.')
                            ->danger()
                            ->send();
                        return;
                    }

                    $disk = $this->findVideoDisk($videoPath);
                    
                    if (!$disk) {
                        Notification::make()
                            ->title('File Not Found')
                            ->body("The video file '{$videoPath}' was not found in any storage disk.")
                            ->danger()
                            ->send();
                        return;
                    }

                    return Storage::disk($disk)->download($videoPath);
                })
                ->hidden(fn () => !$this->record->video_path || !$this->findVideoDisk($this->record->video_path)),
        ];
    }

    protected function getFormSchema(): array
    {
        $videoPath = $this->record->video_path;
        $disk = $videoPath ? $this->findVideoDisk($videoPath) : null;
        $videoExists = $disk && Storage::disk($disk)->exists($videoPath);
        $videoUrl = $videoExists ? route('video.feedback.preview', ['id' => $this->record->id]) : null;

        return [
            \Filament\Forms\Components\TextInput::make('name')
                ->label('Name')
                ->disabled(),
            \Filament\Forms\Components\TextInput::make('email')
                ->label('Email')
                ->disabled(),
            \Filament\Forms\Components\Textarea::make('feedback')
                ->label('Feedback')
                ->disabled(),
            \Filament\Forms\Components\ViewField::make('video')
                ->label('Video Preview')
                ->view('filament.components.video-player')
                ->viewData([
                    'videoUrl' => $videoUrl,
                    'videoPath' => $videoPath,
                    'videoExists' => $videoExists
                ]),
        ];
    }

    /**
     * Find which disk contains the video file
     */
    protected function findVideoDisk(string $path): ?string
    {
        // List of disks to check (only existing configured disks)
        $disksToCheck = ['public', 'local'];
        
        foreach ($disksToCheck as $disk) {
            try {
                if (Storage::disk($disk)->exists($path)) {
                    return $disk;
                }
            } catch (\Exception $e) {
                // Skip disks that are not configured
                continue;
            }
        }
        
        return null;
    }
}