<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AboutFacilityContentResource\Pages;
use App\Models\AboutFacilityContent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AboutFacilityContentResource extends Resource
{
    protected static ?string $model = AboutFacilityContent::class;

    protected static ?string $navigationGroup = 'Facilities';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canCreate(): bool
    {
        return static::getModel()::count() === 0;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\RichEditor::make('about_facility_content')
                    ->label('Detailed About Content')
                    ->toolbarButtons([
                        'bold',
                        'italic',
                        'underline',
                        'strike',
                        'blockquote',
                        'codeBlock',
                        'link',
                        'bulletList',
                        'orderedList',
                        'h1',
                        'h2',
                        'h3',
                        'h4',
                        'horizontalRule',
                        'undo',
                        'redo',
                    ])
                    ->maxLength(5000)
                    ->columnSpanFull()

                    ->fileAttachmentsDisk('public')
                    ->fileAttachmentsDirectory('uploads/facilities/content')
                    ->fileAttachmentsVisibility('public')
                    ->maxLength(5000)
                    ->columnSpanFull()
                    ->helperText('Detailed content for the about facility page'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('about_facility_content')
                    ->label('About Content')
                    ->limit(100) // show only first 100 characters
                    ->wrap(), // wraps instead of cutting words in the middle

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
        // ->bulkActions([
        //     Tables\Actions\BulkActionGroup::make([
        //         Tables\Actions\DeleteBulkAction::make(),
        //     ]),
        // ]);
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
            'index' => Pages\ListAboutFacilityContents::route('/'),
            'create' => Pages\CreateAboutFacilityContent::route('/create'),
            'edit' => Pages\EditAboutFacilityContent::route('/{record}/edit'),
        ];
    }
}
