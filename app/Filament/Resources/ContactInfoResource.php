<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactInfoResource\Pages;
use App\Filament\Resources\ContactInfoResource\RelationManagers;
use App\Models\ContactInfo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Textarea;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContactInfoResource extends Resource
{
    protected static ?string $model = ContactInfo::class;

    protected static ?string $navigationGroup = 'Contact';
    protected static ?string $navigationIcon = 'heroicon-o-phone';

    protected static ?string $navigationLabel = 'Contact Information';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Contact Information')
                    ->schema([
                        Forms\Components\TextInput::make('booking_contact_number')
                            ->label('Booking Contact Number')
                            ->tel()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('whatsapp_number')
                            ->label('Whatsapp Number')
                            ->tel()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('reception_contact_number')
                            ->label('Reception Contact Number')
                            ->tel()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('address')
                            ->label('Address')
                            ->maxLength(255),

                    ])->columns(2),

                Forms\Components\Section::make('Social Links')
                    ->schema([
                        Forms\Components\TextInput::make('facebook_link')
                            ->label('Facebook Link')
                            ->url(),
                        Forms\Components\TextInput::make('youtube_link')
                            ->label('Youtube Link')
                            ->url(),
                        Forms\Components\TextInput::make('instagram_link')
                            ->label('Instagram Link')
                            ->url(),
                    ])->columns(2),

                Forms\Components\Section::make('Open Hours')
                    ->schema([
                        Textarea::make('open_hours')
                            ->label('Open Hours')
                            ->columnSpanFull()
                    ]),
            ]);

    }

     public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            // ->bulkActions([
            //     Tables\Actions\BulkActionGroup::make([
            //         Tables\Actions\DeleteBulkAction::make(),
            //     ]),
            // ])
            ->paginated(false);
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
            'index' => Pages\ListContactInfos::route('/'),
            'create' => Pages\CreateContactInfo::route('/create'),
            'edit' => Pages\EditContactInfo::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        // Allow only one record to exist
        return static::getModel()::count() === 0;
    }
}
