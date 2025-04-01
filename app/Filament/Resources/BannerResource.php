<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BannerResource\Pages;
use App\Models\Banner;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class BannerResource extends Resource
{
    protected static ?string $model = Banner::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Banery';
    protected static ?string $slug = 'banery';
    protected static ?string $navigationGroup = 'CMS Zarządzanie';

// Formularz od banerów
    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            FileUpload::make('image')
                ->image()
                ->maxSize(8192)
                ->directory('banners')
                ->label('Zdjęcie')
                ->required(),

            Select::make('page')
                ->label('Podstrona')
                ->options([
                    'index' => 'Strona Główna',
                    'onas' => 'O Nas',
                    'kontakt' => 'Kontakt',
                    'oferta' => 'Oferta',
                    'testimonials' => 'Testimonials',
                    'historie' => 'Wasze Historie',
                    'blog' => 'Blog',
                    'portfolio' => 'Portfolio',
                ]) 
                ->required(),
        ]);
    }
// Tabela z banerami
    public static function table(Tables\Table $table): Tables\Table
    {
       return $table->columns([
        TextColumn::make('image')
            ->label('Miniatura')
            ->formatStateUsing(fn ($state) => "
                <div class='gallery-item' style='width: 80px; height: 80px; overflow: hidden; border-radius: 8px;'>
                    <img src='/storage/{$state}' alt='Zdjęcie' style='width: 100%; height: 100%; object-fit: cover;'>
                </div>
            ")
            ->html(),
        TextColumn::make('page')
        ->label('Podstrona')
        ->sortable(),
        
    ])

        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
            Tables\Actions\Action::make('optimize')
                ->icon('heroicon-s-pencil')
                ->label('webp')
                ->visible(function ($record) {
                    return !preg_match('/\.webp$/i', $record->image);
                })
                ->action(function ($record) {
                    dispatch(new \App\Jobs\OptimizeBannerJob($record));
                    \Filament\Notifications\Notification::make()
                        ->title('Optymalizacja została uruchomiona.')
                        ->success()
                        ->send();
                }),
        ])    
    ->bulkActions([
        Tables\Actions\DeleteBulkAction::make(),
    ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBanners::route('/'),
            'create' => Pages\CreateBanner::route('/create'),
            'edit' => Pages\EditBanner::route('/{record}/edit'),
        ];
    }
}
