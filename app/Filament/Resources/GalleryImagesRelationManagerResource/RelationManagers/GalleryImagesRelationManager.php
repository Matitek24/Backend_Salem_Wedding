<?php

namespace App\Filament\Resources\GalleryImagesRelationManagerResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class GalleryImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'galleryImages';
    protected static ?string $recordTitleAttribute = 'image_path';
    protected static ?string $label = 'Galeria';
    protected static ?string $pluralLabel = 'Galerie';

    // Formularz edycji pojedynczego rekordu – pozostaje bez zmian
    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\FileUpload::make('image_path')
                ->image()
                ->directory('gallery-images')
                ->required()
                ->label('Zdjęcie'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('image_path')
                    ->label('Miniatura')
                    ->formatStateUsing(fn ($state) => "<img src='/storage/{$state}' width='80' height='80' style='object-fit: cover; border-radius: 8px;' />")
                    ->html(),
            ])
            ->defaultSort('order')
            ->reorderable('order')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                // Akcja tworzenia, umożliwiająca przesyłanie wielu zdjęć
                Tables\Actions\CreateAction::make()
                    ->label('Dodaj Zdjęcia')
                    ->form([
                        Forms\Components\FileUpload::make('images')
                            ->multiple()
                            ->image()
                            ->directory('gallery-images')
                            ->required()
                            ->label('Zdjęcia'),
                    ])
                    ->action(function (array $data): void {
                        foreach ($data['images'] as $file) {
                            // Dla każdego przesłanego pliku tworzymy osobny rekord
                            $this->getRelationship()->create([
                                'image_path' => $file,
                            ]);
                        }
                    }),
            ]);
    }
}
