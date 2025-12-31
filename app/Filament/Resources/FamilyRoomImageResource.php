<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FamilyRoomImageResource\Pages;
use App\Models\FamilyRoomImage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class FamilyRoomImageResource extends Resource
{
    protected static ?string $model = FamilyRoomImage::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

   protected static ?string $navigationGroup = '4bed Family Room Page';
protected static ?string $navigationLabel = 'Gallery ';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('image_path')
                    ->label('Image')
                    ->required()
                    ->directory('family-room-images')
                    ->image()
                    ->maxSize(2048),
                Forms\Components\TextInput::make('sort_order')
                    ->label('Sort Order')
                    ->numeric()
                    ->default(0),
                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_path') // Corrected namespace
                    ->label('Image')
                    ->disk('public') // Add the disk where images are stored
                    ->searchable(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Sort Order')
                    ->sortable(),
                Tables\Columns\BooleanColumn::make('is_active')
                    ->label('Active'),
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
                Tables\Filters\Filter::make('is_active')
                    ->label('Active Images')
                    ->query(fn (Builder $query): Builder => $query->where('is_active', true)),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFamilyRoomImages::route('/'),
            'create' => Pages\CreateFamilyRoomImage::route('/create'),
            'edit' => Pages\EditFamilyRoomImage::route('/{record}/edit'),
        ];
    }
}