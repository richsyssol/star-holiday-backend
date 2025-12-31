<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VideoResource\Pages;
use App\Models\Video;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class VideoResource extends Resource
{
    protected static ?string $model = Video::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';
       protected static ?string $navigationGroup = "videoss";
    
    protected static ?string $navigationLabel = "Six Bedroom About";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('type')
                    ->options([
                        'upload' => 'Upload Video',
                        'youtube' => 'YouTube URL',
                    ])
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        // Reset fields when type changes
                        if ($state === 'upload') {
                            $set('youtube_url', null);
                        } else {
                            $set('video_path', null);
                        }
                    }),
                Forms\Components\FileUpload::make('video_path')
                    ->label('Video File')
                    ->directory('videos')
                    ->visibility('public')
                    ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/ogg'])
                    ->maxSize(102400) // 100MB
                    ->required(fn ($get) => $get('type') === 'upload')
                    ->hidden(fn ($get) => $get('type') !== 'upload')
                    ->helperText('Maximum file size: 100MB. Supported formats: MP4, WebM, OGG'),
                Forms\Components\TextInput::make('youtube_url')
                    ->label('YouTube URL')
                    ->url()
                    ->required(fn ($get) => $get('type') === 'youtube')
                    ->hidden(fn ($get) => $get('type') !== 'youtube')
                    ->helperText('Enter the full YouTube URL (e.g., https://www.youtube.com/watch?v=...)')
                    ->afterStateUpdated(function ($state, callable $set) {
                        // Automatically extract video ID from YouTube URL
                        if (preg_match('/^(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&]+)/', $state, $matches)) {
                            $videoId = $matches[1];
                            $set('youtube_id', $videoId);
                        }
                    }),
                Forms\Components\Hidden::make('youtube_id'), // Store extracted YouTube ID
                Forms\Components\FileUpload::make('thumbnail')
                    ->label('Video Thumbnail')
                    ->image()
                    ->directory('video-thumbnails')
                    ->visibility('public')
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio('16:9')
                    ->imageResizeTargetWidth('1280')
                    ->imageResizeTargetHeight('720')
                    ->helperText('Recommended aspect ratio: 16:9 (1280×720 pixels)'),
                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->required()
                    ->default(true)
                    ->helperText('Toggle to show/hide this video on the website'),
                Forms\Components\TextInput::make('order')
                    ->numeric()
                    ->default(0)
                    ->minValue(0)
                    ->helperText('Set display order (lower numbers appear first)'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('type')
                    ->label('Type')
                    ->icon(fn (string $state): string => match ($state) {
                        'upload' => 'heroicon-o-cloud-upload',
                        'youtube' => 'heroicon-o-globe-alt',
                    })
                    ->size('md'),
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label('Thumbnail')
                    ->size(80)
                    ->circular(),
                Tables\Columns\TextColumn::make('video_path')
                    ->label('Uploaded Video')
                    ->formatStateUsing(fn ($state) => $state ? 'Uploaded' : 'None')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('youtube_url')
                    ->label('YouTube URL')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('order')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'upload' => 'Uploaded Video',
                        'youtube' => 'YouTube URL',
                    ])
                    ->label('Video Type'),
                Tables\Filters\Filter::make('is_active')
                    ->label('Active Videos Only')
                    ->query(fn ($query) => $query->where('is_active', true)),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->iconButton()
                    ->size('sm'),
                Tables\Actions\DeleteAction::make()
                    ->iconButton()
                    ->size('sm'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Delete Selected'),
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Activate Selected')
                        ->action(fn ($records) => $records->each->update(['is_active' => true]))
                        ->icon('heroicon-o-check-circle'),
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Deactivate Selected')
                        ->action(fn ($records) => $records->each->update(['is_active' => false]))
                        ->icon('heroicon-o-x-circle'),
                ]),
            ])
            ->defaultSort('order', 'asc')
            ->reorderable('order');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVideos::route('/'),
            'create' => Pages\CreateVideo::route('/create'),
            'edit' => Pages\EditVideo::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return static::getModel()::where('is_active', true)->count() > 0 ? 'success' : 'danger';
    }
}