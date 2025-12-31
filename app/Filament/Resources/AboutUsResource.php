<?php
// app/Filament/Resources/AboutUsResource.php

namespace App\Filament\Resources;

use App\Filament\Resources\AboutUsResource\Pages;
use App\Models\AboutUs;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AboutUsResource extends Resource
{
    protected static ?string $model = AboutUs::class;

    protected static ?string $navigationIcon = 'heroicon-o-information-circle';

    protected static ?string $navigationGroup = 'Home Page';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('welcome_title')
                    ->required()
                    ->maxLength(255)
                    ->default('Welcome to'),
                
                Forms\Components\TextInput::make('main_title')
                    ->required()
                    ->maxLength(255)
                    ->default('Star Holiday Home Hill Resort'),
                
                Forms\Components\Textarea::make('description_1')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull(),
                
                Forms\Components\Textarea::make('description_2')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull(),
                
                Forms\Components\TextInput::make('button_text')
                    ->default('LEARN MORE')
                    ->maxLength(255),
                
                Forms\Components\FileUpload::make('image_1')
                    ->label('First Image')
                    ->disk('public')
                    ->directory('about-us')
                    ->preserveFilenames()
                    ->image()
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio('3:4')
                    ->imageResizeTargetWidth('600')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->visibility('public'),
                
                Forms\Components\FileUpload::make('image_2')
                    ->label('Second Image')
                    ->image()
                    ->disk('public')
                    ->directory('about-us')
                    ->preserveFilenames()
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio('3:4')
                    ->imageResizeTargetWidth('600')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->visibility('public'),
                
                Forms\Components\FileUpload::make('image_3')
                    ->label('Third Image (Wide)')
                    ->image()
                    ->disk('public')
                    ->directory('about-us')
                    ->preserveFilenames()
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio('16:9')
                    ->imageResizeTargetWidth('800')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->visibility('public'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('main_title')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('image_1')
                    ->disk('public')
                    ->label('Image 1'),
                Tables\Columns\ImageColumn::make('image_2')
                    ->disk('public')
                    ->label('Image 2'),
                Tables\Columns\ImageColumn::make('image_3')
                    ->disk('public')
                    ->label('Image 3'),
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
            'index' => Pages\ListAboutUs::route('/'),
            'create' => Pages\CreateAboutUs::route('/create'),
            'edit' => Pages\EditAboutUs::route('/{record}/edit'),
        ];
    }
}