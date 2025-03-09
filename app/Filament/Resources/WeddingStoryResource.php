<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WeddingStoryResource\Pages;
use App\Models\WeddingStory;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;

class WeddingStoryResource extends Resource
{
    protected static ?string $model = WeddingStory::class;

    protected static ?string $navigationIcon = 'heroicon-o-camera';
    protected static ?string $navigationLabel = 'Historie';
    protected static ?string $navigationGroup = 'Admin';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('couple_names')->required(),
            Forms\Components\Textarea::make('description')->required(),
            Forms\Components\FileUpload::make('thumbnail')
                ->image()
                ->disk('public')
                ->directory('uploads/wedding_thumbnails')
                ->required(),
            Forms\Components\TextInput::make('youtube_link')->url(),
            Forms\Components\TextInput::make('gallery_link')->url(),
            Forms\Components\TextInput::make('access_code')->required(),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('couple_names')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('description')->limit(50),
            Tables\Columns\ImageColumn::make('thumbnail'),
            Tables\Columns\TextColumn::make('youtube_link'),
            Tables\Columns\TextColumn::make('gallery_link'),
            Tables\Columns\TextColumn::make('access_code')->limit(10),
        ])->filters([
            // Tu można dodać filtry
        ])->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListWeddingStories::route('/'),
            'create' => Pages\CreateWeddingStory::route('/create'),
            'edit'   => Pages\EditWeddingStory::route('/{record}/edit'),
        ];
    }
}
