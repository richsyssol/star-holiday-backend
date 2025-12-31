<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Models\Review;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';
    
    protected static ?string $navigationGroup = 'Content Management';
    
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Review Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->disabled(),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->disabled(),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->required()
                            ->maxLength(20)
                            ->disabled(),
                        Forms\Components\Textarea::make('review')
                            ->required()
                            ->columnSpanFull()
                            ->disabled(),
                        Forms\Components\TextInput::make('rating')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(5)
                            ->disabled(),
                        Forms\Components\DateTimePicker::make('created_at')
                            ->label('Submitted At')
                            ->disabled(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('rating')
                    ->numeric()
                    ->sortable()
                    ->color(fn (int $state): string => match (true) {
                        $state >= 4 => 'success',
                        $state === 3 => 'warning',
                        default => 'danger',
                    })
                    ->icon(fn (int $state): string => match (true) {
                        $state >= 4 => 'heroicon-m-star',
                        $state === 3 => 'heroicon-m-exclamation-triangle',
                        default => 'heroicon-m-x-circle',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Submitted On'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('rating')
                    ->options([
                        1 => '1 Star',
                        2 => '2 Stars',
                        3 => '3 Stars',
                        4 => '4 Stars',
                        5 => '5 Stars',
                    ]),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('submitted_from'),
                        Forms\Components\DatePicker::make('submitted_until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['submitted_from'],
                                fn ($query, $date) => $query->whereDate('created_at', '>=', $date)
                            )
                            ->when(
                                $data['submitted_until'],
                                fn ($query, $date) => $query->whereDate('created_at', '<=', $date)
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('No reviews yet')
            ->emptyStateDescription('Customer reviews will appear here once submitted.')
            ->emptyStateIcon('heroicon-o-chat-bubble-left-ellipsis');
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
            'index' => Pages\ListReviews::route('/'),
            // Remove the 'view' page reference if you haven't created it
        ];
    }
    
    public static function canCreate(): bool
    {
        return false; // Disable creating reviews from admin panel
    }
    
    public static function canEdit($record): bool
    {
        return false; // Disable editing reviews from admin panel
    }
}