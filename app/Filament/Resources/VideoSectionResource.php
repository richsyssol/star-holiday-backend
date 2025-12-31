<?php
// app/Filament/Resources/VideoSectionResource.php

namespace App\Filament\Resources;

use App\Filament\Resources\VideoSectionResource\Pages;
use App\Models\VideoSection;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class VideoSectionResource extends Resource
{
    protected static ?string $model = VideoSection::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';
    
    protected static ?string $navigationGroup = 'Home Page';
     protected static ?string $navigationLabel = 'Video two ';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                    // Forms\Components\TextInput::make('title')
                    //     ->required()
                    //     ->maxLength(255),
                        
                Forms\Components\Select::make('video_type')
                    ->options([
                        'youtube' => 'YouTube Video',
                        'upload' => 'Upload Video',
                    ])
                    ->required()
                    ->reactive()
                    ->default('youtube'),
                    
                Forms\Components\TextInput::make('youtube_url')
                    ->label('YouTube URL')
                    ->url()
                    ->required(fn (callable $get) => $get('video_type') === 'youtube')
                    ->hidden(fn (callable $get) => $get('video_type') !== 'youtube')
                    ->helperText('Example: https://www.youtube.com/watch?v=VIDEO_ID'),
                    
                Forms\Components\FileUpload::make('uploaded_video_path')
                    ->disk('public')
                    ->preserveFilenames()
                    ->label('Video File')
                    ->directory('video-sections')
                    ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/ogg'])
                    ->maxSize(102400) // 100MB
                    ->required(fn (callable $get) => $get('video_type') === 'upload')
                    ->hidden(fn (callable $get) => $get('video_type') !== 'upload')
                    ->helperText('Maximum file size: 100MB. Supported formats: MP4, WebM, OGG'),
                    
                // Forms\Components\Toggle::make('autoplay')
                //     ->default(true),
                    
                // Forms\Components\Toggle::make('muted')
                //     ->default(true),
                    
                // Forms\Components\Toggle::make('loop')
                //     ->default(true),
                    
                // Forms\Components\Toggle::make('show_controls')
                //     ->label('Show Controls')
                //     ->default(false),
                    
                Forms\Components\TextInput::make('sort_order')
                    ->numeric()
                    ->default(0),
                    
                Forms\Components\Toggle::make('is_active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('video_type')
                    ->badge()
                    ->color(fn ($record) => $record ? match ($record->video_type) {
                        'youtube' => 'success',
                        'upload' => 'info',
                        default => 'gray',
                    } : 'gray')
                    ->formatStateUsing(fn ($record) => $record ? ucfirst($record->video_type) : 'N/A'),
                    
                Tables\Columns\TextColumn::make('youtube_url')
                    ->label('YouTube URL')
                    ->visible(fn ($record) => $record && $record->video_type === 'youtube')
                    ->limit(30)
                    ->default('N/A'),
                    
                Tables\Columns\TextColumn::make('uploaded_video_path')
                    ->label('Uploaded Video')
                    ->visible(fn ($record) => $record && $record->video_type === 'upload')
                    ->formatStateUsing(fn ($state) => $state ? 'Uploaded' : 'No Video')
                    ->default('N/A'),
                    
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('is_active')
                    ->query(fn (Builder $query): Builder => $query->where('is_active', true)),
                    
                Tables\Filters\SelectFilter::make('video_type')
                    ->options([
                        'youtube' => 'YouTube',
                        'upload' => 'Uploaded',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (VideoSection $record) {
                        // Delete the associated video file
                        if ($record->uploaded_video_path) {
                            Storage::disk('public')->delete($record->uploaded_video_path);
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->before(function ($records) {
                        // Delete all associated video files
                        foreach ($records as $record) {
                            if ($record->uploaded_video_path) {
                                Storage::disk('public')->delete($record->uploaded_video_path);
                            }
                        }
                    }),
            ])
            ->emptyStateHeading('No video sections yet')
            ->emptyStateDescription('Create your first video section to get started.')
            ->emptyStateIcon('heroicon-o-film');
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
            'index' => Pages\ListVideoSections::route('/'),
            'create' => Pages\CreateVideoSection::route('/create'),
            'edit' => Pages\EditVideoSection::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    
    public static function getNavigationBadgeColor(): string|array|null
    {
        return static::getModel()::count() > 0 ? 'success' : 'gray';
    }
}