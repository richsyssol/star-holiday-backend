<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CoupleRoomAboutResource\Pages;
use App\Filament\Resources\CoupleRoomAboutResource\RelationManagers;
use App\Models\CoupleRoomAbout;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;

class CoupleRoomAboutResource extends Resource
{
    protected static ?string $model = CoupleRoomAbout::class;

    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationGroup = "Couple Room Page";

    protected static ?string $navigationLabel = "Couple Room About";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Basic Information')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('tagline')
                            ->required()
                            ->maxLength(255),
                        Repeater::make('descriptions')
                            ->schema([
                                Textarea::make('description')
                                    ->required()
                                    ->label('Description')
                            ])
                            ->defaultItems(1)
                            ->columnSpanFull(),
                    ]),
                
                Section::make('Room Specifications')
                    ->schema([
                        TextInput::make('specs.size')
                            ->label('Room Size')
                            ->required(),
                        TextInput::make('specs.bed')
                            ->label('Bed Type')
                            ->required(),
                    ]),
                
                Section::make('Amenities')
                    ->schema([
                        Repeater::make('amenities')
                            ->schema([
                                TextInput::make('amenity')
                                    ->required()
                                    ->label('Amenity')
                            ])
                            ->defaultItems(1)
                            ->columnSpanFull(),
                    ]),
                
                Section::make('Images')
                    ->schema([
                        FileUpload::make('images')
                            ->image()
                            ->preserveFilenames()
                            ->disk('public')
                            ->directory('uploads/couple-room-images') // Updated path
                            ->multiple()
                            ->required()
                            ->columnSpanFull(),
                    ]),
                
                Section::make('Booking Button')
                    ->schema([
                        TextInput::make('booking_button.text')
                            ->label('Button Text')
                            ->default('Book Now'),
                        TextInput::make('booking_button.url')
                            ->label('Button URL')
                            ->default('/bookform'),
                    ]),
                
                Section::make('Styling')
                    ->schema([
                        TextInput::make('styling.background')
                            ->label('Background CSS')
                            ->default('linear-gradient(to bottom right, #f9fafb, #ffffff, #f3f4f6)'),
                        TextInput::make('styling.maxWidth')
                            ->label('Max Width')
                            ->default('1600px'),
                    ]),
                
                Toggle::make('is_active')
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
            'index' => Pages\ListCoupleRoomAbouts::route('/'),
            'create' => Pages\CreateCoupleRoomAbout::route('/create'),
            'edit' => Pages\EditCoupleRoomAbout::route('/{record}/edit'),
        ];
    }
    
    public static function canCreate(): bool
    {
        // Allow only one active record
        return CoupleRoomAbout::where('is_active', true)->count() === 0;
    }
}