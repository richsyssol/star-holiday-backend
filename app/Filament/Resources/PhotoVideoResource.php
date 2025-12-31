<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PhotoVideoResource\Pages;
use App\Models\PhotoVideo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class PhotoVideoResource extends Resource
{
    protected static ?string $model = PhotoVideo::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';

    protected static ?string $navigationGroup = 'Media';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('video_path')
                    ->label('Video File')
                    ->acceptedFileTypes([
                        'video/mp4',
                        'video/avi', 
                        'video/mov',
                        'video/wmv',
                        'video/webm',
                        'video/mkv',
                    ])
                    ->disk('public')
                    ->directory('photo-videos')
                    ->preserveFilenames()
                    ->maxSize(20480) // 20MB
                    ->helperText('Upload a video file (Max: 20MB) OR provide a video URL below')
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('video_url')
                    ->label('Video URL')
                    ->url()
                    ->maxLength(500)
                    ->helperText('Provide a video URL (YouTube, Vimeo, etc.) OR upload a video file above')
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('caption')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(1),

                Forms\Components\TextInput::make('order')
                    ->numeric()
                    ->default(0)
                    ->columnSpan(1),

                Forms\Components\Toggle::make('is_active')
                    ->default(true)
                    ->columnSpan(1),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('caption')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('type')
                    ->getStateUsing(fn ($record) => $record->video_path ? 'Uploaded File' : 'External URL')
                    ->badge()
                    ->color(fn ($state) => $state === 'Uploaded File' ? 'primary' : 'success')
                    ->sortable(),

                Tables\Columns\TextColumn::make('order')
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('is_active')
                    ->options([
                        '1' => 'Active',
                        '0' => 'Inactive',
                    ])
                    ->label('Status'),

                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'uploaded' => 'Uploaded Files',
                        'external' => 'External URLs',
                    ])
                    ->query(function ($query, $data) {
                        if ($data['value'] === 'uploaded') {
                            return $query->whereNotNull('video_path');
                        } elseif ($data['value'] === 'external') {
                            return $query->whereNotNull('video_url');
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (PhotoVideo $record) {
                        // Delete video file using Storage facade
                        if ($record->video_path && Storage::disk('public')->exists($record->video_path)) {
                            Storage::disk('public')->delete($record->video_path);
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function ($records) {
                            foreach ($records as $record) {
                                // Delete video file using Storage facade
                                if ($record->video_path && Storage::disk('public')->exists($record->video_path)) {
                                    Storage::disk('public')->delete($record->video_path);
                                }
                            }
                        }),
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
            'index' => Pages\ListPhotoVideos::route('/'),
            'create' => Pages\CreatePhotoVideo::route('/create'),
            'edit' => Pages\EditPhotoVideo::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return static::getModel()::count() > 10 ? 'warning' : 'primary';
    }
}