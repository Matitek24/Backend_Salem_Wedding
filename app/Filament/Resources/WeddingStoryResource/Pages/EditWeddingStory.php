<?php

namespace App\Filament\Resources\WeddingStoryResource\Pages;

use App\Filament\Resources\WeddingStoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWeddingStory extends EditRecord
{
    protected static string $resource = WeddingStoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
