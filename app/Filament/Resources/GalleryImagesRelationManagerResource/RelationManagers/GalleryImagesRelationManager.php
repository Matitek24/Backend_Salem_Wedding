<?php

namespace App\Filament\Resources\GalleryImagesRelationManagerResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Notifications\Notification;

class GalleryImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'galleryImages';
    protected static ?string $recordTitleAttribute = 'image_path';
    protected static ?string $label = 'Galeria';
    protected static ?string $pluralLabel = 'Galerie';

    public function table(Table $table): Table
    {
        return $table
            ->contentGrid([
                'sm' => 2, // 2 kolumny na małych ekranach
                'md' => 3, // 3 kolumny na średnich ekranach
                'lg' => 4, // 4 kolumny na dużych ekranach
                'xl' => 4, // 5 kolumn na większych ekranach
            ])
            ->columns([
                Grid::make(1)
                    ->schema([
                        Tables\Columns\TextColumn::make('image_path')
                            ->label('Miniatura')
                            ->formatStateUsing(fn ($state) => "
                                <div class='gallery-item'>
                                    <img src='/storage/{$state}' alt='Zdjęcie'>
                                </div>
                            ")
                            ->html(),
                    ]),
            ])
            ->defaultSort('order')
            ->reorderable('order')
            ->actions([
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('optimize')
                ->icon('heroicon-s-pencil')
                    ->label('webp')
                    ->visible(function ($record) {
                        // Sprawdza, czy nazwa pliku nie kończy się na .webp (ignorując wielkość liter)
                        return !preg_match('/\.webp$/i', $record->image_path);
                    })
                    ->action(function ($record) {
                        dispatch(new \App\Jobs\OptimizeImageJob($record));
                        \Filament\Notifications\Notification::make()
                            ->title('Optymalizacja została uruchomiona.')
                            ->success()
                            ->send();
                    }),
            ])
            
            ->headerActions([
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
                            $this->getOwnerRecord()->galleryImages()->create([
                                'image_path' => $file,
                            ]);
                        }
                    }),
            ]);
    }
}
