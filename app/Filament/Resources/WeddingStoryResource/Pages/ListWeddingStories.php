<?php

namespace App\Filament\Resources\WeddingStoryResource\Pages;

use App\Filament\Resources\WeddingStoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWeddingStories extends ListRecords
{
    protected static string $resource = WeddingStoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
