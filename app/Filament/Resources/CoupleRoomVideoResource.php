<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CoupleRoomVideoResource\Pages;
use App\Filament\Resources\CoupleRoomVideoResource\RelationManagers;
use App\Models\CoupleRoomVideo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CoupleRoomVideoResource extends Resource
{
    protected static ?string $model = CoupleRoomVideo::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';
    protected static ?string $navigationGroup = 'Couple Room Page';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('video_url')
                    ->label('YouTube URL')
                    ->url()
                    ->maxLength(255)
                    ->helperText('Enter a YouTube embed URL. Example: https://www.youtube.com/embed/VIDEO_ID')
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $set('video_file', null);
                        }
                    }),
                Forms\Components\FileUpload::make('video_file')
                    ->label('Upload Video')
                    ->helperText('Upload a video file (max: 20MB)')
                    ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/ogg'])
                    ->maxSize(20480)
                    ->directory('couple-room-videos')
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $set('video_url', null);
                        }
                    }),
                // Forms\Components\Textarea::make('review')
                //     ->maxLength(65535)
                //     ->columnSpanFull(),
                Forms\Components\Toggle::make('is_active')
                    ->required()
                    ->default(true),
                Forms\Components\TextInput::make('sort_order')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->numeric()
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
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListCoupleRoomVideos::route('/'),
            'create' => Pages\CreateCoupleRoomVideo::route('/create'),
            'edit' => Pages\EditCoupleRoomVideo::route('/{record}/edit'),
        ];
    }
}