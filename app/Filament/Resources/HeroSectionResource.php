<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HeroSectionResource\Pages;
use App\Models\HeroSection;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile; 

class HeroSectionResource extends Resource
{
    protected static ?string $model = HeroSection::class;
    protected static ?string $navigationIcon = 'heroicon-o-camera';
    protected static ?string $navigationGroup = 'Home Page';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('subtitle')
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->rows(3),
                        Forms\Components\TextInput::make('cta_text')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('cta_link')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('icon')
                            ->options([
                                'leaf' => 'Leaf',
                                'utensils' => 'Utensils',
                                'users' => 'Users',
                                'star' => 'Star',
                                'map-pin' => 'Map Pin',
                            ])
                            ->nullable(),
                        Forms\Components\FileUpload::make('image_url')
                            ->directory('hero-images') // This will store in public/uploads/hero-images
                            ->disk('public') // Using the 'public' disk we configured
                            ->image()
                            ->required()
                            ->preserveFilenames()
                            ->getUploadedFileNameForStorageUsing(
                                fn (TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                                    ->prepend('hero-')
                            ),
                        Forms\Components\Toggle::make('is_active')
                            ->default(true),
                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_url')
                    ->label('Image')
                    ->disk('public')
                    ->url(fn ($record) => asset('uploads/' . $record->image_url)),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subtitle')
                    ->searchable(),
                Tables\Columns\BooleanColumn::make('is_active')
                    ->label('Active'),
                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('is_active')
                    ->label('Active Slides')
                    ->query(fn (Builder $query): Builder => $query->where('is_active', true)),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHeroSections::route('/'),
            'create' => Pages\CreateHeroSection::route('/create'),
            'edit' => Pages\EditHeroSection::route('/{record}/edit'),
        ];
    }
}