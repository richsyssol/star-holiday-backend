<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FestivalGalleryResource\Pages;
use App\Models\FestivalCategory;
use App\Models\FestivalGallery;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FestivalGalleryResource extends Resource
{
    protected static ?string $model = FestivalGallery::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Gallery Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Festival Gallery')
                    ->schema([
                        Forms\Components\Select::make('category_id')
                            ->label('Category')
                            ->options(FestivalCategory::active()->ordered()->pluck('name', 'id'))
                            ->required()
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                            ])
                            ->createOptionUsing(function (array $data) {
                                $category = FestivalCategory::create($data);
                                return $category->id;
                            }),
                    ]),
                
                Forms\Components\Section::make('Gallery Images')
                    ->schema([
                        Forms\Components\FileUpload::make('images')
                            ->label('')
                            ->disk('public')
                            ->preserveFilenames()
                            ->multiple()
                            ->image()
                            ->directory('festival-gallery')
                            ->reorderable()
                            ->appendFiles()
                            ->required()
                            ->columnSpanFull(),
                    ]),
                
                Forms\Components\Section::make('Settings')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->default(true)
                            ->required(),
                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('images')
                    ->label('Preview')
                    ->disk('public')
                    ->size(40)
                    ->stacked()
                    ->limit(3)
                    ->limitedRemainingText(),
                Tables\Columns\TextColumn::make('category.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\Filter::make('is_active')
                    ->query(fn ($query) => $query->where('is_active', true))
                    ->label('Active only'),
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
            ->defaultSort('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFestivalGalleries::route('/'),
            'create' => Pages\CreateFestivalGallery::route('/create'),
            'edit' => Pages\EditFestivalGallery::route('/{record}/edit'),
        ];
    }
}