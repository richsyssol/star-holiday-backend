<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RestaurantSectionResource\Pages;
use App\Models\RestaurantSection;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RestaurantSectionResource extends Resource
{
    protected static ?string $model = RestaurantSection::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Home Page';

    protected static ?string $navigationLabel = 'Restaurant Section';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('heading')
                    ->required()
                    ->maxLength(255)
                    ->label('Section Heading')
                    ->columnSpanFull(),
                    
                Forms\Components\Textarea::make('description')
                    ->rows(3)
                    ->label('Description')
                    ->columnSpanFull(),
                    
                Forms\Components\Repeater::make('images')
                    ->relationship('images')
                    ->reorderableWithButtons()
                    ->schema([
                        Forms\Components\FileUpload::make('image_path')
                            ->disk('public') // Changed to use the 'public' disk which points to uploads
                            ->preserveFilenames()
                            ->label('Image')
                            ->image()
                            ->directory('restaurant-images')
                            ->required()
                            ->columnSpan(2)
                            ->visibility('public')
                            ->preserveFilenames()
                            ->imageResizeMode('cover')
                            ->imageResizeTargetWidth('1200')
                            ->imageResizeTargetHeight('800'),
                            
                        Forms\Components\TextInput::make('order')
                            ->label('Display Order')
                            ->numeric()
                            ->default(0)
                            ->columnSpan(1),
                    ])
                    ->defaultItems(1)
                    ->columns(3)
                    ->grid(2)
                    ->createItemButtonLabel('Add Image')
                    ->collapsible()
                    ->itemLabel(function (array $state): ?string {
                        // Extract just the filename from the path for the label
                        if (isset($state['image_path']) && is_string($state['image_path'])) {
                            return basename($state['image_path']);
                        }
                        return 'New Image';
                    })
                    ->label('Restaurant Images')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('heading')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('description')
                    ->limit(50)
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('images_count')
                    ->counts('images')
                    ->label('Images Count')
                    ->badge()
                    ->color('success'),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListRestaurantSections::route('/'),
            'create' => Pages\CreateRestaurantSection::route('/create'),
            'edit' => Pages\EditRestaurantSection::route('/{record}/edit'),
        ];
    }
    
    public static function canCreate(): bool
    {
        return RestaurantSection::count() === 0;
    }
}