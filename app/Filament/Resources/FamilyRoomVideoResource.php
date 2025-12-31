<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FamilyRoomVideoResource\Pages;
use App\Filament\Resources\FamilyRoomVideoResource\RelationManagers;
use App\Models\FamilyRoomVideo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FamilyRoomVideoResource extends Resource
{
    protected static ?string $model = FamilyRoomVideo::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';
 protected static ?string $navigationGroup = '4bed Family Room Page';
protected static ?string $navigationLabel = 'Videos ';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Video Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\Toggle::make('is_active')
                            ->default(true)
                            ->required(),
                            
                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),
                    ]),
                    
                Forms\Components\Section::make('Video Source')
                    ->description('Choose either a YouTube URL or upload a video file')
                    ->schema([
                        Forms\Components\TextInput::make('video_url')
                            ->label('YouTube URL')
                            ->url()
                            ->maxLength(500)
                            ->placeholder('https://www.youtube.com/embed/...')
                            ->nullable()
                            ->reactive(),
                            
                        Forms\Components\FileUpload::make('video_file')
                            ->label('Upload Video')
                            ->disk('public')
                            ->directory('family-room-videos')
                            ->acceptedFileTypes(['video/mp4', 'video/avi', 'video/mov', 'video/wmv'])
                            ->maxSize(51200) // 50MB
                            ->nullable()
                            ->visible(fn (Forms\Get $get): bool => !$get('video_url')),
                            
                        Forms\Components\Placeholder::make('video_preview')
                            ->content(fn ($record): string => $record ? $record->video_source : '')
                            ->visible(fn ($record): bool => $record && ($record->video_url || $record->video_file)),
                    ]),
                    
                // Forms\Components\Section::make('Review')
                //     ->schema([
                //         Forms\Components\Textarea::make('review')
                //             ->maxLength(65535)
                //             ->nullable(),
                //     ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('is_active')
                    ->query(fn (Builder $query): Builder => $query->where('is_active', true)),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('sort_order', 'asc');
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
            'index' => Pages\ListFamilyRoomVideos::route('/'),
            'create' => Pages\CreateFamilyRoomVideo::route('/create'),
            'edit' => Pages\EditFamilyRoomVideo::route('/{record}/edit'),
        ];
    }
}