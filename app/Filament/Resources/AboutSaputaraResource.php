<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AboutSaputaraResource\Pages;
use App\Models\AboutSaputara;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class AboutSaputaraResource extends Resource
{
    protected static ?string $model = AboutSaputara::class;

    protected static ?string $navigationIcon = 'heroicon-o-information-circle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // About Saputara Section
                Forms\Components\Section::make('About Saputara')
                    ->schema([
                        Forms\Components\RichEditor::make('about_content')
                            ->label('Content')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('about_image')
                            ->label('Image')
                            ->disk('public')
                            ->preserveFilenames()
                            ->image()
                            ->directory('about-saputara')
                            ->required(),
                    ])
                    ->columns(1),

                // Sightseeing Section
                Forms\Components\Section::make('Saputara Sightseeing')
                    ->schema([
                        Forms\Components\RichEditor::make('sightseeing_content')
                            ->label('Content')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('sightseeing_image')
                            ->label('Image')
                            ->disk('public')
                            ->preserveFilenames()
                            ->image()
                            ->directory('saputara-sightseeing')
                            ->required(),
                    ])
                    ->columns(1),

                // Video Testimonials Section
                Forms\Components\Section::make('Video Testimonials')
                    ->schema([
                        Forms\Components\Repeater::make('video_testimonials')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Person Name')
                                    ->required(),
                                Forms\Components\TextInput::make('video_url')
                                    ->label('Video URL (YouTube or other)')
                                    ->url()
                                    ->nullable(),
                                Forms\Components\FileUpload::make('video_file')
                                    ->label('Or Upload Video File')
                                    ->disk('public')
                                    ->preserveFilenames()
                                    ->acceptedFileTypes(['video/*'])
                                    ->directory('testimonial-videos')
                                    ->getUploadedFileNameForStorageUsing(
                                        fn(TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                                            ->prepend('testimonial-'),
                                    )
                                    ->nullable(),
                                Forms\Components\Textarea::make('review')
                                    ->label('Review')
                                    ->required()
                                    ->rows(3),
                            ])
                            ->addActionLabel('Add Testimonial')
                            ->columns(1)
                            ->grid(2)
                    ]),

                // Gallery Section
                Forms\Components\Section::make('Gallery')
                    ->schema([
                        Forms\Components\FileUpload::make('gallery')
                            ->label('Images')
                            ->disk('public')
                            ->preserveFilenames()
                            ->multiple()
                            ->image()
                            ->directory('saputara-gallery')
                            ->reorderable()
                            ->appendFiles()
                            ->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\ImageColumn::make('about_image')
                    ->disk('public')
                    ->label('About Image'),
                Tables\Columns\ImageColumn::make('sightseeing_image')
                    ->disk('public')
                    ->label('Sightseeing Image'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    ->sortable(),
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
            'index' => Pages\ListAboutSaputara::route('/'),
            'create' => Pages\CreateAboutSaputara::route('/create'),
            'edit' => Pages\EditAboutSaputara::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        // Allow only one record to exist
        return static::getModel()::count() === 0;
    }
}