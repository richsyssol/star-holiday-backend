<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SixRoomImageResource\Pages;
use App\Filament\Resources\SixRoomImageResource\RelationManagers;
use App\Models\SixRoomImage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SixRoomImageResource extends Resource
{
    protected static ?string $model = SixRoomImage::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';
 protected static ?string $navigationGroup = "Six Bedroom Page";
    
    protected static ?string $navigationLabel = "Gallery";


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                
                Forms\Components\Textarea::make('description')
                    ->nullable()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                
                Forms\Components\FileUpload::make('image_path')
                    ->label('Image')
                    ->required()
                    ->image()
                    ->directory('six-room-images') // Folder in public/uploads
                    ->disk('public') // Use the 'public' disk
                    ->visibility('public')
                    ->preserveFilenames()
                    ->maxSize(2048), // 2MB max
                
                Forms\Components\TextInput::make('sort_order')
                    ->numeric()
                    ->default(0)
                    ->required(),
                
                Forms\Components\Toggle::make('is_active')
                    ->required()
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Image')
                    ->disk('public'), // Use the 'public' disk
                
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('sort_order')
                    ->numeric()
                    ->sortable(),
                
                Tables\Columns\BooleanColumn::make('is_active')
                    ->label('Active'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('is_active')
                    ->query(fn (Builder $query): Builder => $query->where('is_active', true))
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order', 'asc');
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
            'index' => Pages\ListSixRoomImages::route('/'),
            'create' => Pages\CreateSixRoomImage::route('/create'),
            'edit' => Pages\EditSixRoomImage::route('/{record}/edit'),
        ];
    }
}