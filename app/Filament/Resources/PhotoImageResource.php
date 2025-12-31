<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PhotoImageResource\Pages;
use App\Models\PhotoImage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class PhotoImageResource extends Resource
{
    protected static ?string $model = PhotoImage::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Media';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('image_path')
                    ->label('Image')
                    ->image()
                    ->required()
                    ->disk('public')
                    ->directory('photo-images')
                    ->preserveFilenames()
                    ->maxSize(5120) // 5MB
                    ->helperText('Upload image (Max: 5MB)')
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('caption')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(1),

                Forms\Components\TextInput::make('order')
                    ->numeric()
                    ->default(0)
                    ->columnSpan(1),

                Forms\Components\Toggle::make('is_active')
                    ->default(true)
                    ->columnSpan(1),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Image')
                    ->disk('public')
                    ->size(50)
                    ->sortable(),

                Tables\Columns\TextColumn::make('caption')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('order')
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('is_active')
                    ->options([
                        '1' => 'Active',
                        '0' => 'Inactive',
                    ])
                    ->label('Status'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (PhotoImage $record) {
                        // Delete image file
                        if ($record->image_path && Storage::disk('public')->exists($record->image_path)) {
                            Storage::disk('public')->delete($record->image_path);
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function ($records) {
                            foreach ($records as $record) {
                                if ($record->image_path && Storage::disk('public')->exists($record->image_path)) {
                                    Storage::disk('public')->delete($record->image_path);
                                }
                            }
                        }),
                ]),
            ])
            ->defaultSort('order', 'asc')
            ->reorderable('order');
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
            'index' => Pages\ListPhotoImages::route('/'),
            'create' => Pages\CreatePhotoImage::route('/create'),
            'edit' => Pages\EditPhotoImage::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return static::getModel()::count() > 10 ? 'warning' : 'primary';
    }
}