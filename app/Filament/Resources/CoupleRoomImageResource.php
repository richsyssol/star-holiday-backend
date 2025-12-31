<?php
// app/Filament/Resources/CoupleRoomImageResource.php

namespace App\Filament\Resources;

use App\Models\CoupleRoomImage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CoupleRoomImageResource extends Resource
{
    protected static ?string $model = CoupleRoomImage::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Couple Room Page';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->default('Gallery Image')
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($set, $state) {
                        $set('slug', Str::slug($state));
                    }),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('image_path')
                    ->label('Image')
                    ->required()
                    ->image()
                    ->directory('couple-room-images')
                    ->visibility('public')
                    ->preserveFilenames()
                    ->maxSize(2048) // 2MB max
                    ->afterStateUpdated(function ($set, $state) {
                        // Auto-generate title from filename if not set
                        if ($state) {
                            $filename = pathinfo($state->getClientOriginalName(), PATHINFO_FILENAME);
                            $set('title', Str::title(str_replace(['-', '_'], ' ', $filename)));
                        }
                    }),
                Forms\Components\TextInput::make('sort_order')
                    ->numeric()
                    ->default(0),
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
                    ->size(80),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
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
                Tables\Filters\Filter::make('is_active')
                    ->query(fn ($query) => $query->where('is_active', true))
                    ->label('Only Active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function ($record) {
                        // Delete the image file when the record is deleted
                        Storage::delete($record->image_path);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function ($records) {
                            // Delete all image files for the selected records
                            foreach ($records as $record) {
                                Storage::delete($record->image_path);
                            }
                        }),
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
            'index' => \App\Filament\Resources\CoupleRoomImageResource\Pages\ListCoupleRoomImages::route('/'),
            'create' => \App\Filament\Resources\CoupleRoomImageResource\Pages\CreateCoupleRoomImage::route('/create'),
            'edit' => \App\Filament\Resources\CoupleRoomImageResource\Pages\EditCoupleRoomImage::route('/{record}/edit'),
        ];
    }
}