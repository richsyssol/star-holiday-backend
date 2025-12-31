<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SixbedroomAboutResource\Pages;
use App\Filament\Resources\SixbedroomAboutResource\RelationManagers;
use App\Models\SixbedroomAbout;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SixbedroomAboutResource extends Resource
{
    protected static ?string $model = SixbedroomAbout::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';
    
    protected static ?string $navigationGroup = "Six Bedroom Page";
    
    protected static ?string $navigationLabel = "Six Bedroom About";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('tagline')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Repeater::make('descriptions')
                            ->schema([
                                Forms\Components\Textarea::make('description')
                                    ->required()
                                    ->label('Description')
                            ])
                            ->defaultItems(1)
                            ->columnSpanFull(),
                    ]),
                
                Forms\Components\Section::make('Room Specifications')
                    ->schema([
                        Forms\Components\KeyValue::make('specs')
                            ->keyLabel('Spec Name')
                            ->valueLabel('Spec Value')
                            ->columnSpanFull(),
                    ]),
                
                Forms\Components\Section::make('Amenities')
                    ->schema([
                        Forms\Components\Repeater::make('amenities')
                            ->schema([
                                Forms\Components\TextInput::make('amenity')
                                    ->required()
                                    ->label('Amenity')
                            ])
                            ->defaultItems(1)
                            ->columnSpanFull(),
                    ]),
                
                Forms\Components\Section::make('Images')
                    ->schema([
                        Forms\Components\FileUpload::make('images')
                            ->image()
                            ->preserveFilenames()
                            ->disk('public') // This uses the 'public' disk from filesystems.php
                            ->directory('sixbed-room-img') // This will upload to public/uploads/sixbed-room-images
                            ->multiple()
                            ->required()
                            ->columnSpanFull(),
                    ]),
                
                Forms\Components\Section::make('Booking Button')
                    ->schema([
                        Forms\Components\TextInput::make('booking_button.text')
                            ->label('Button Text')
                            ->default('Book Now'),
                        Forms\Components\TextInput::make('booking_button.url')
                            ->label('Button URL')
                            ->default('/bookform'),
                    ]),
                
                Forms\Components\Section::make('Styling')
                    ->schema([
                        Forms\Components\TextInput::make('styling.background')
                            ->label('Background CSS')
                            ->default('linear-gradient(to bottom right, #f9fafb, #ffffff, #f3f4f6)'),
                        Forms\Components\TextInput::make('styling.maxWidth')
                            ->label('Max Width')
                            ->default('1600px'),
                    ]),
                
                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tagline')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Last Updated'),
            ])
            ->filters([
                Tables\Filters\Filter::make('is_active')
                    ->query(fn (Builder $query): Builder => $query->where('is_active', true))
                    ->label('Only Active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListSixbedroomAbouts::route('/'),
            'create' => Pages\CreateSixbedroomAbout::route('/create'),
            'edit' => Pages\EditSixbedroomAbout::route('/{record}/edit'),
        ];
    }   
    
    public static function canCreate(): bool
    {
        // Allow only one active record (same as couple room)
        return SixbedroomAbout::where('is_active', true)->count() === 0;
    }
}