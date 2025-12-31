<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AboutSectionResource\Pages;
use App\Models\AboutSection;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AboutSectionResource extends Resource
{
    protected static ?string $model = AboutSection::class;

    protected static ?string $navigationIcon = 'heroicon-o-information-circle';

    protected static ?string $navigationGroup = 'About Us Page';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('content')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('image_path')
                    ->disk('public') // This now points to the uploads directory
                    ->preserveFilenames()
                    ->label('Background Image')
                    ->image()
                    ->directory('about-sections') // This will store in public/uploads/about-sections
                    ->required(),
                Forms\Components\TextInput::make('height')
                    ->numeric()
                    ->minValue(100)
                    ->maxValue(1000)
                    ->default(300)
                    ->suffix('px'),
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
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('image_path')
                    ->disk('public') // This now points to the uploads directory
                    ->label('Image'),
                Tables\Columns\TextColumn::make('height')
                    ->numeric()
                    ->sortable()
                    ->suffix(' px'),
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
            'index' => Pages\ListAboutSections::route('/'),
            'create' => Pages\CreateAboutSection::route('/create'),
            'edit' => Pages\EditAboutSection::route('/{record}/edit'),
        ];
    }
}