<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SixBedroomVideoResource\Pages;
use App\Filament\Resources\SixBedroomVideoResource\RelationManagers;
use App\Models\SixBedroomVideo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SixBedroomVideoResource extends Resource
{
    protected static ?string $model = SixBedroomVideo::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';

    protected static ?string $navigationGroup = "Six Bedroom Page";
    
    protected static ?string $navigationLabel = "Video";

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
                    ->maxLength(500)
                    ->helperText('Paste a YouTube embed URL (e.g., https://www.youtube.com/embed/VIDEO_ID)')
                    ->nullable(),
                Forms\Components\FileUpload::make('video_file')
                    ->label('Upload Video')
                    ->helperText('Upload a video file (max: 20MB)')
                    ->acceptedFileTypes(['video/mp4', 'video/avi', 'video/mov', 'video/wmv'])
                    ->maxSize(20480)
                    ->nullable()
                    ->disk('public')
                    ->directory('six-bedroom-videos'),
                // Forms\Components\Textarea::make('review')
                //     ->nullable(),
                Forms\Components\Toggle::make('is_active')
                    ->default(true),
                Forms\Components\TextInput::make('sort_order')
                    ->numeric()
                    ->default(0),
            ])
            ->columns(1);
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
            'index' => Pages\ListSixBedroomVideos::route('/'),
            'create' => Pages\CreateSixBedroomVideo::route('/create'),
            'edit' => Pages\EditSixBedroomVideo::route('/{record}/edit'),
        ];
    }
}