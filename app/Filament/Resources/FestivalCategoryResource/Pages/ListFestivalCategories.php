<?php

namespace App\Filament\Resources\FestivalCategoryResource\Pages;

use App\Filament\Resources\FestivalCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFestivalCategories extends ListRecords
{
    protected static string $resource = FestivalCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
