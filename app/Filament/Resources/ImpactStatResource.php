<?php

namespace App\Filament\Resources;

use App\Models\ImpactStat;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Filters\Filter;
use App\Filament\Resources\ImpactStatResource\Pages;

class ImpactStatResource extends Resource
{
    protected static ?string $model = ImpactStat::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationGroup = 'Home Page';
     protected static ?string $navigationLabel = 'Hotel Statistics';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                TextInput::make('label')
                    ->required()
                    ->maxLength(255),
                TextInput::make('number')
                    ->numeric()
                    ->required(),
                TextInput::make('suffix')
                    ->maxLength(10)
                    ->default('+'),
                TextInput::make('decimals')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(5)
                    ->default(0),
                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->default(true),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('label'),
                TextColumn::make('number'),
                TextColumn::make('suffix'),
                BooleanColumn::make('is_active'),
                TextColumn::make('sort_order'),
            ])
            ->filters([
                Filter::make('is_active')
                    ->query(fn (Builder $query) => $query->where('is_active', true))
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListImpactStats::route('/'),
            'create' => Pages\CreateImpactStat::route('/create'),
            'edit' => Pages\EditImpactStat::route('/{record}/edit'),
        ];
    }
}