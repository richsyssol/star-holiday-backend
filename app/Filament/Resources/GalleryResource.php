<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GalleryResource\Pages;
use App\Filament\Resources\GalleryResource\RelationManagers;
use App\Models\Gallery;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GalleryResource extends Resource
{
    protected static ?string $model = Gallery::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';
    
    protected static ?string $navigationGroup = "Gallery";
    
    protected static ?string $navigationLabel = "Gallery Images";
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Gallery Images')
                    ->schema([
                        Forms\Components\Repeater::make('gallery_images')
                            ->schema([
                                Forms\Components\FileUpload::make('url')
                                    ->image()
                                    ->preserveFilenames()
                                    ->disk('public')
                                    ->directory('gallery')
                                    ->required(),
                                Forms\Components\Select::make('category')
                                    ->options([
                                        'restaurant' => 'Restaurant',
                                        'rooms' => 'Rooms',
                                        'activities' => 'Activities',
                                    ])
                                    ->required(),
                            ])
                            ->grid(2)
                            ->defaultItems(1)
                            ->columnSpanFull(),
                    ]),
                    
                Forms\Components\Section::make('Amenities Highlights')
                    ->schema([
                        Forms\Components\Repeater::make('amenities_highlights')
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->required(),
                                Forms\Components\Select::make('icon')
                                    ->options([
                                        'restaurant' => 'Restaurant',
                                        'rooms' => 'Rooms',
                                        'activities' => 'Activities',
                                    ])
                                    ->required(),
                                Forms\Components\Textarea::make('description')
                                    ->required(),
                                Forms\Components\FileUpload::make('images')
                                    ->image()
                                    ->preserveFilenames()
                                    ->disk('public')
                                    ->directory('amenities')
                                    ->multiple()
                                    ->maxFiles(3)
                                    ->required(),
                            ])
                            ->defaultItems(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Tables\Columns\TextColumn::make('created_at')
                //     ->dateTime()
                //     ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    // ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            // ->bulkActions([
            //     Tables\Actions\BulkActionGroup::make([
            //         Tables\Actions\DeleteBulkAction::make(),
            //     ]),
            // ]);
            ->paginated(false);
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
            'index' => Pages\ListGalleries::route('/'),
            'create' => Pages\CreateGallery::route('/create'),
            'edit' => Pages\EditGallery::route('/{record}/edit'),
        ];
    }
    
    public static function canCreate(): bool
    {
        return Gallery::count() === 0;
    }
}
