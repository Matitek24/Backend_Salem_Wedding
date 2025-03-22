<?php

namespace App\Filament\Resources\GalleryImagesRelationManagerResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\Layout\Grid;
class GalleryImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'galleryImages';  //relacja z modelem w Models GalleryImage 
    protected static ?string $recordTitleAttribute = 'image_path';
    protected static ?string $label = 'Galeria';
    protected static ?string $pluralLabel = 'Galerie';

    public function table(Table $table): Table
    {
        return $table
            ->contentGrid([
                'sm' => 2, 
                'md' => 3,
                'lg' => 4,
                'xl' => 4,
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
            ]) // zdjecie w tabelii 
            ->defaultSort('order')
            ->reorderable('order')
            ->actions([
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('optimize')
                ->icon('heroicon-s-pencil')
                    ->label('webp')
                    ->visible(function ($record) {
                        return !preg_match('/\.webp$/i', $record->image_path);
                    }) // sprwadza czy konczy sie na webp walidacji do widocznosci 
                    ->action(function ($record) {
                        dispatch(new \App\Jobs\OptimizeImageJob($record));
                        \Filament\Notifications\Notification::make()
                            ->title('Optymalizacja została uruchomiona.')
                            ->success()
                            ->send();
                    }),
            ]) 
            // optymalizacja do webp guzik
            
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
            // dodawanie zdjecia do bazy danych action tworzy nam nowy adres do zdjecia zapisujac go w bazie danych
    }
}
