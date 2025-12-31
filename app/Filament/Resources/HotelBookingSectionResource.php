<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HotelBookingSectionResource\Pages;
use App\Models\HotelBookingSection;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class HotelBookingSectionResource extends Resource
{
    protected static ?string $model = HotelBookingSection::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';   
     protected static ?string $navigationGroup = 'Home Page';
 protected static ?string $navigationLabel = 'Video one ';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')
                ->required()
                ->maxLength(255)
                ->columnSpanFull(),

            // Forms\Components\Textarea::make('description')
            //     ->maxLength(65535)
            //     ->columnSpanFull()
            //     ->rows(4),

            Forms\Components\TextInput::make('button_text')
                ->default('BOOK NOW')
                ->maxLength(255)
                ->required(),

            Forms\Components\TextInput::make('button_link')
                ->default('/bookform')
                ->maxLength(255)
                ->required()
                ->prefix('/'),

            Forms\Components\Radio::make('video_type')
                ->options([
                    'url' => 'YouTube URL',
                    'upload' => 'Upload Video',
                ])
                ->default('url')
                ->inline()
                ->reactive()
                ->required(),

            Forms\Components\TextInput::make('video_url')
                ->label('YouTube Video URL')
                ->url()
                ->maxLength(255)
                ->hidden(fn (callable $get) => $get('video_type') !== 'url')
                ->rules(['nullable', 'url'])
                ->helperText('Enter any YouTube URL (watch, embed, or youtu.be) - Example: https://www.youtube.com/watch?v=VIDEO_ID')
                ->placeholder('https://www.youtube.com/watch?v=VIDEO_ID')
                ->columnSpanFull(),

            Forms\Components\FileUpload::make('uploaded_video')
                ->disk('public')
                ->preserveFilenames()
                ->label('Upload Video')
                ->directory('hotel-videos')
                ->visibility('public')
                ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/ogg'])
                ->maxSize(10240) // 10MB
                ->helperText('Maximum file size: 10MB. Supported formats: MP4, WebM, OGG')
                ->hidden(fn (callable $get) => $get('video_type') !== 'upload')
                ->columnSpanFull(),

            Forms\Components\Toggle::make('is_active')
                ->required()
                ->default(true)
                ->inline()
                ->helperText('Only one active section will be displayed on the website'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('button_text')
                    ->searchable()
                    ->badge()
                    ->color('primary'),
                    
                TextColumn::make('video_type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'url' => 'success',
                        'upload' => 'info',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'url' => 'YouTube',
                        'upload' => 'Uploaded Video',
                    }),
                    
                IconColumn::make('is_active')
                    ->boolean()
                    ->sortable()
                    ->action(function ($record) {
                        $record->update(['is_active' => !$record->is_active]);
                    }),
                    
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('is_active')
                    ->label('Active Sections')
                    ->query(fn (Builder $query) => $query->where('is_active', true)),
                    
                Filter::make('video_type')
                    ->form([
                        Forms\Components\Select::make('video_type')
                            ->options([
                                'url' => 'YouTube URL',
                                'upload' => 'Uploaded Video',
                            ])
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['video_type'])) {
                            $query->where('video_type', $data['video_type']);
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Activate Selected')
                        ->icon('heroicon-o-check-circle')
                        ->action(function ($records) {
                            $records->each->update(['is_active' => true]);
                        }),
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Deactivate Selected')
                        ->icon('heroicon-o-x-circle')
                        ->action(function ($records) {
                            $records->each->update(['is_active' => false]);
                        }),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array    
    {
        return [
            'index' => Pages\ListHotelBookingSections::route('/'),
            'create' => Pages\CreateHotelBookingSection::route('/create'),
            'edit' => Pages\EditHotelBookingSection::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    
    public static function getNavigationBadgeColor(): string|array|null
    {
        return static::getModel()::where('is_active', true)->exists() ? 'success' : 'gray';
    }
}