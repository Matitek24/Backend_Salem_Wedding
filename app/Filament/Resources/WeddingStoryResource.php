<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WeddingStoryResource\Pages;
use App\Models\WeddingStory;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class WeddingStoryResource extends Resource
{
    protected static ?string $model = WeddingStory::class;

    protected static ?string $navigationIcon = 'heroicon-o-camera';
    protected static ?string $navigationLabel = 'Historie';
    protected static ?string $navigationGroup = 'CMS Zarządzanie';

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
            Forms\Components\TextInput::make('promo_link')
                ->label('Promo Link YouTube')
                ->url(),
            Forms\Components\TextInput::make('gallery_link')->url(),
            Forms\Components\TextInput::make('access_code')
                ->label('Kod dostępu')
                ->default(fn () => Str::random(8))
                ->required()
                ->suffixAction(
                    Forms\Components\Actions\Action::make('generate')
                        ->label('Generuj')
                        ->icon('heroicon-o-arrow-path')
                        ->action(fn ($set) => $set('access_code', Str::random(8)))
                ),
            Forms\Components\Radio::make('is_public')
                ->label('Czy historia jest publiczna?')
                ->options([
                    1 => 'Tak',
                    0 => 'Nie'
                ])
                ->default(0)
                ->inline()
                ->live()
                ->afterStateUpdated(function ($state, callable $set, $get, $record) {
                    if ($state && !$record) {
                        $highestOrder = WeddingStory::where('is_public', true)->max('order');
                        // Ustaw nową wartość jako najwyższą + 1
                        $set('order', ($highestOrder + 1));
                    }
                }),
            Forms\Components\TextInput::make('order')
                ->label('Kolejność wyświetlania')
                ->numeric()
                ->default(0)
                ->helperText('Niższa wartość = wyższa pozycja')
                ->visible(fn (callable $get) => $get('is_public') == 1),
            // Dodane pola wyświetlane tylko gdy historia jest publiczna
            Forms\Components\Textarea::make('additional_text')
                ->label('Dodatkowy opis')
                ->visible(fn (callable $get) => $get('is_public') == 1),
            Forms\Components\TextInput::make('extra_gallery_link')
                ->label('Dodatkowy link do galerii')
                ->url()
                ->visible(fn (callable $get) => $get('is_public') == 1),
                Forms\Components\Section::make('Visualizacja')
                ->visible(fn (callable $get) => $get('thumbnail'))
                ->schema([
                    Forms\Components\Placeholder::make('Previeww')
                        ->content(fn ($record) => new HtmlString(
                            $record && $record->thumbnail && $record->couple_name && $record->description ? 
                            '<div style="text-align: center; padding-bottom: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); max-width: 400px; margin: 0 auto; background-color: #fff;">
                                <img src="' . asset('storage/' . e($record->thumbnail)) . '" alt="Zdjęcie pary" style="max-width: 100%; height: auto; border-radius: 10px 10px 0 0; display: block;" />
                                <h2 style="margin-top: 15px; margin-bottom: 10px; font-weight: 600; color:black; font-size:20px;">' . e($record->couple_name ?? 'Brak nazwy') . '</h2>
                                <hr style="border: 0; height: 1px; background-color: #e0e0e0; margin: 15px 0;" />
                                <p style="margin-bottom: 0; color: #555;">' . e($record->description ?? 'Brak opisu') . '</p>
                            </div>' : ''
                        ))
                        ->columnSpan(2),
                ]),
                    ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('couple_names')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('description')->limit(50),
            Tables\Columns\BooleanColumn::make('is_public')->label('Publiczna'),
        ])->filters([
            //
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
