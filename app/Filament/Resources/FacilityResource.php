<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FacilityResource\Pages;
use App\Filament\Resources\FacilityResource\RelationManagers;
use App\Models\Facility;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class FacilityResource extends Resource
{
    protected static ?string $model = Facility::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationGroup = 'Facilities';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Facility Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        
                        Forms\Components\TextInput::make('subtitle')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        
                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),
                        
                        Forms\Components\FileUpload::make('image')
                            ->label('Facility Image')
                            ->image()
                            ->directory('/facilities')
                            ->preserveFilenames()
                            ->disk('public') // Matches your disk configuration
                            ->visibility('public')
                            ->maxSize(2048)
                            // ->getUploadedFileNameForStorageUsing(
                            //     fn (TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                            //         ->prepend(time() . '-'),
                            // )
                            ->columnSpanFull(),
                        
                        Forms\Components\TextInput::make('order')
                            ->numeric()
                            ->default(0),
                        
                        Forms\Components\Toggle::make('is_active')
                            ->default(true)
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Image')
                    ->disk('public')
                    ->circular(),
                
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('subtitle')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('order')
                    ->numeric()
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('is_active')
                    ->query(fn (Builder $query): Builder => $query->where('is_active', true))
                    ->label('Only Active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->action(function (Facility $record) {
                    // Delete associated image when facility is deleted
                    if ($record->image) {
                        Storage::disk('public')->delete('uploads/facilities/' . $record->image);
                    }
                    $record->delete();
                }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->action(function ($records) {
                        foreach ($records as $record) {
                            // Delete associated images when facilities are deleted in bulk
                            if ($record->image) {
                                Storage::disk('public')->delete('uploads/facilities/' . $record->image);
                            }
                            $record->delete();
                        }
                    }),
                ]),
            ])
            ->defaultSort('order', 'asc');
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
            'index' => Pages\ListFacilities::route('/'),
            'create' => Pages\CreateFacility::route('/create'),
            'edit' => Pages\EditFacility::route('/{record}/edit'),
        ];
    }
}