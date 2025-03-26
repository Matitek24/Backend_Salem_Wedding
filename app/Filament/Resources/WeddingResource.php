<?php
namespace App\Filament\Resources;

use App\Filament\Resources\WeddingResource\Pages;
use App\Filament\Widgets\WeddingCalendar;
use App\Models\Wedding;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\Actions\Action;


class WeddingResource extends Resource
{
    protected static ?string $model = Wedding::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationLabel = 'Wesela';
    protected static ?string $navigationGroup = 'Admin';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\TextInput::make('imie1')
                ->label('Imię Panny Młodej')
                ->required(),
            Forms\Components\TextInput::make('nazwisko1')
                ->label('Nazwisko Panny Młodej')
                ->required(),
            Forms\Components\TextInput::make('imie2')
                ->label('Imię Pana Młodego')
                ->required(),
            Forms\Components\TextInput::make('nazwisko2')
                ->label('Nazwisko Pana Młodego')
                ->required(),
            Forms\Components\TextInput::make('telefon_panny')->label('Telefon Panny Młodej')->required(),
            Forms\Components\TextInput::make('telefon_pana')->label('Telefon Pana Młodego')->required(),
            Forms\Components\DatePicker::make('data')->label('Data Wesela')->required(),
            Forms\Components\Select::make('pakiet')
            ->label('Pakiet')
            ->options([
                'film' => 'Film',
                'foto' => 'Foto',
                'foto+film' => 'Foto+Film',
                'foto+film+fotoplener' => 'Foto+Film+Fotoplener',
                'foto+fotoplener' => 'Foto+Fotoplener',
            ])
            ->required(),
            Forms\Components\TextInput::make('sala')->label('Sala Weselna')->default(''),
            Forms\Components\TextInput::make('koscol')->label('Kościół')->default(''),
            Forms\Components\TextInput::make('liczba_gosci')->label('Liczba Gości')->numeric()->default(0),
            Forms\Components\Select::make('typ_wesela')
            ->label('Typ Wesela')
            ->options([
                'boho' => 'Boho',
                'klasyczny' => 'Klasyczny',
                'plenerowy' => 'Plenerowy',
            ])
            ->default('boho'),
            Forms\Components\Textarea::make('uwagi')
                ->label('Uwagi')
                ->rows(3)
                ->placeholder('Dodaj dodatkowe informacje')
                ->maxLength(500),
            Forms\Components\FileUpload::make('photo')
                ->label('Zdjęcie pary')
                ->image() 
                ->directory('admin-photos') 
                ->nullable(),
                Forms\Components\Select::make('typ_zamowienia')
                ->label('Typ Zamówienia')
                ->options([
                    'rezerwacja' => 'Rezerwacja',
                    'umowa' => 'Wesele Umowa',
                ])
                ->default('rezerwacja')
                ->required(),

                
                Forms\Components\Section::make('Podgląd zdjęcia')
                    ->hidden(fn ($record) => !$record || !$record->photo)
                    ->schema([
                        Forms\Components\Placeholder::make('photo_preview')
                            ->content(fn ($record) => new HtmlString('<img src="' . asset('storage/' . $record->photo) . '" alt="Zdjęcie pary" 
                                                    style="max-width: 40%; border-radius: 10px; box-shadow: 0px 4px 6px rgba(0,0,0,0.1);">'))
                            ->disableLabel()
                            ->columnSpan(2), // Ustawia szerokość na 50%
                
                        Forms\Components\Actions::make([
                            Action::make('delete_photo')
                                ->label('Usuń zdjęcie')
                                ->color('danger')
                                ->icon('heroicon-o-trash')
                                ->requiresConfirmation()
                                ->action(function ($record) {
                                    if ($record->photo) {
                                        Storage::delete('public/' . $record->photo); // Usuwa plik z storage
                                        $record->photo = null; // Usuwa ścieżkę z bazy
                                        $record->save(); // Zapisuje zmiany
                                    }
                                })
                                ->hidden(fn ($record) => !$record || !$record->photo),
                        ]),
                    ]),                
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('imie1')
                    ->label('Panna Młoda')
                    ->sortable(),
                Tables\Columns\TextColumn::make('imie2')
                    ->label('Pan Młody')
                    ->sortable(),
                Tables\Columns\TextColumn::make('data')
                    ->label('Data Wesela')
                    ->sortable(),
                // Tables\Columns\TextColumn::make('liczba_gosci')
                //     ->label('Liczba Gości')
                //     ->sortable()
                //     ->color(fn ($state) => $state == 0 ? 'danger' : 'default'),
                // Nowe kolumny (opcjonalnie)
                Tables\Columns\TextColumn::make('typ_zamowienia')
                    ->label('Typ Zamówienia')
                    ->color(fn ($state) => $state == "rezerwacja" ? 'danger' : 'default'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\Action::make('export')
                    ->label('Eksportuj do Excela')
                    ->url(route('weddings.export'))
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->label('Usuń elementy'),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListWeddings::route('/'),
            'create' => Pages\CreateWedding::route('/create'),
            'edit'   => Pages\EditWedding::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            WeddingCalendar::class,
        ];
    }
}
