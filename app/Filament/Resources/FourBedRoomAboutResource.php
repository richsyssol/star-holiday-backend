<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FourBedRoomAboutResource\Pages;
use App\Models\FourBedRoomAbout;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class FourBedRoomAboutResource extends Resource
{
    protected static ?string $model = FourBedRoomAbout::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';
     protected static ?string $navigationGroup = '4bed Family Room Page';
protected static ?string $navigationLabel = 'Four bedded room About   ';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Room Information')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Room Title')
                            ->required(),
                        Forms\Components\Textarea::make('tagline')
                            ->label('Tagline')
                            ->rows(2),
                        Forms\Components\Repeater::make('descriptions')
                            ->label('Description Paragraphs')
                            ->schema([
                                Forms\Components\Textarea::make('description')
                                    ->label('Paragraph')
                                    ->rows(2)
                                    ->required(),
                            ])
                            ->defaultItems(1)
                            ->columnSpanFull()
                            ->afterStateHydrated(function ($state, $set) {
                                // Convert JSON string to array for editing
                                if (is_string($state)) {
                                    $state = json_decode($state, true);
                                    $set('descriptions', $state);
                                }
                            })
                            ->afterStateUpdated(function ($state, $set) {
                                // Ensure it's stored as array for proper handling
                                $set('descriptions', $state);
                            }),
                    ]),
                
                Forms\Components\Section::make('Room Specifications')
                    ->schema([
                        Forms\Components\KeyValue::make('specs')
                            ->label('Specifications (Key-Value pairs)')
                            ->keyLabel('Feature')
                            ->valueLabel('Description')
                            ->reorderable()
                            ->afterStateHydrated(function ($state, $set) {
                                // Convert JSON string to array for editing
                                if (is_string($state)) {
                                    $state = json_decode($state, true);
                                    $set('specs', $state);
                                }
                            }),
                    ]),
                
                Forms\Components\Section::make('Amenities')
                    ->schema([
                        Forms\Components\Repeater::make('amenities')
                            ->schema([
                                Forms\Components\TextInput::make('amenity')
                                    ->label('Amenity')
                                    ->required(),
                            ])
                            ->defaultItems(1)
                            ->afterStateHydrated(function ($state, $set) {
                                // Convert JSON string to array for editing
                                if (is_string($state)) {
                                    $state = json_decode($state, true);
                                    $set('amenities', $state);
                                }
                            }),
                    ]),
                
                Forms\Components\Section::make('Images')
                    ->schema([
                        Forms\Components\FileUpload::make('images')
                            ->label('Room Images')
                            ->multiple()
                            ->image()
                            ->imageResizeMode('cover')
                            ->imageResizeTargetWidth('647')
                            ->imageResizeTargetHeight('427')
                            ->directory('room-images')
                            ->visibility('public')
                            ->preserveFilenames()
                            ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                                return time() . '_' . $file->getClientOriginalName();
                            })
                            ->columnSpanFull()
                            ->afterStateHydrated(function ($state, $set) {
                                // Convert JSON string to array for editing
                                if (is_string($state)) {
                                    $state = json_decode($state, true);
                                    $set('images', $state);
                                }
                            }),
                    ]),
                
                Forms\Components\Section::make('Styling')
                    ->schema([
                        Forms\Components\TextInput::make('styling.background')
                            ->label('Background CSS')
                            ->default('linear-gradient(to bottom right, #f9fafb, #ffffff, #f3f4f6)'),
                        Forms\Components\TextInput::make('styling.maxWidth')
                            ->label('Max Width')
                            ->default('1600px'),
                    ])
                    ->afterStateHydrated(function ($state, $set) {
                        // Convert JSON string to array for editing
                        if (is_string($state)) {
                            $state = json_decode($state, true);
                            $set('styling', $state);
                        }
                    }),
                
                Forms\Components\Section::make('Booking Button')
                    ->schema([
                        Forms\Components\TextInput::make('booking_button.text')
                            ->label('Button Text')
                            ->default('Book Now'),
                        Forms\Components\TextInput::make('booking_button.url')
                            ->label('Button URL')
                            ->default('/bookform'),
                    ])
                    ->afterStateHydrated(function ($state, $set) {
                        // Convert JSON string to array for editing
                        if (is_string($state)) {
                            $state = json_decode($state, true);
                            $set('booking_button', $state);
                        }
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('images')
                    ->label('Primary Image')
                    ->getStateUsing(function ($record) {
                        if ($record->images && is_array($record->images) && count($record->images) > 0) {
                            $firstImage = $record->images[0];
                            // Handle both stored paths and full URLs
                            if (is_string($firstImage) ){
                                if (strpos($firstImage, 'http') === 0) {
                                    return $firstImage;
                                }
                                if (strpos($firstImage, 'uploads/') === 0) {
                                    return asset($firstImage);
                                }
                                return asset('uploads/' . $firstImage);
                            }
                        }
                        return null;
                    })
                    ->circular()
                    ->defaultImageUrl(asset('images/placeholder.jpg')),
                Tables\Columns\TextColumn::make('updated_at')
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
            'index' => Pages\ListFourBedRoomAbouts::route('/'),
            'create' => Pages\CreateFourBedRoomAbout::route('/create'),
            'edit' => Pages\EditFourBedRoomAbout::route('/{record}/edit'),
        ];
    }
}