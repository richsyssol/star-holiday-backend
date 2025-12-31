<?php

namespace App\Filament\Resources\CustomerFeedbackResource\Pages;

use App\Filament\Resources\CustomerFeedbackResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCustomerFeedbacks extends ListRecords
{
    protected static string $resource = CustomerFeedbackResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No create action since canCreate() returns false
        ];
    }
}