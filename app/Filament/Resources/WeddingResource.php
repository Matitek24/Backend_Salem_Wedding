<?php
namespace App\Filament\Resources;

use App\Filament\Resources\WeddingResource\Pages;
use App\Filament\Widgets\WeddingCalendar;
use App\Models\Wedding;
use App\Models\Umowa;
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

    protected static ?string $modelLabel = 'Wesela';
    protected static ?string $pluralModelLabel = 'Wesela';
    protected static ?string $navigationGroup = 'Admin';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Placeholder::make('wedding_header')
                ->content(fn ($record) => new \Illuminate\Support\HtmlString(
                    '<div style="text-align:center; font-size: 2.2em; font-weight: bold; color:#FAFAFF;">' .
                    $record->imie1 . ' & ' . $record->imie2 . ' ' . \Carbon\Carbon::parse($record->data)->format("d-m-Y") .
                    '</div>'
                ))
                ->disableLabel()
                ->columnSpanFull(),
            
                // Sekcja podstawowa – dane wesela
                Forms\Components\Section::make('Strefa Ważna')
                    ->description('Kluczowe informacje o parze i terminie')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('imie1')
                                    ->label('Imię Panny Młodej')
                                    ->required(),
                                Forms\Components\TextInput::make('nazwisko1')
                                    ->label('Nazwisko Panny Młodej')
                                    ->required(),
                                Forms\Components\TextInput::make('telefon_panny')
                                    ->label('Telefon Panny Młodej')
                                    ->required(),
    
                                Forms\Components\TextInput::make('imie2')
                                    ->label('Imię Pana Młodego')
                                    ->required(),
                                Forms\Components\TextInput::make('nazwisko2')
                                    ->label('Nazwisko Pana Młodego')
                                    ->required(),
                                Forms\Components\TextInput::make('telefon_pana')
                                    ->label('Telefon Pana Młodego')
                                    ->required(),
                            ]),
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\DatePicker::make('data')
                                    ->label('Data Wesela')
                                    ->required(),
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
                                Forms\Components\Select::make('typ_zamowienia')
                                    ->label('Typ Zamówienia')
                                    ->options([
                                        'rezerwacja' => 'Rezerwacja',
                                        'umowa' => 'Wesele Umowa',
                                    ])
                                    ->default('rezerwacja')
                                    ->required(),
    
                                Forms\Components\TextInput::make('sala')
                                    ->label('Sala Weselna')
                                    ->required(),
                                Forms\Components\TextInput::make('koscol')
                                    ->label('Kościół')
                                    ->required(),
                            ]),
                    ]),
                // Radio - wybór osoby, dla której generowana będzie umowa
                Forms\Components\Radio::make('contract_person')
                    ->label('Na kogo ma być umowa?')
                    ->options([
                        'bride' => 'Panna Młoda',
                        'groom' => 'Pan Młody',
                    ])
                    ->reactive()
                    ->required()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        if ($state === 'bride') {
                            // Uzupełniamy dane umowy z danych panny młodej
                            $set('umowa.imie', $get('imie1'));
                            $set('umowa.nazwisko', $get('nazwisko1'));
                            $set('umowa.telefon_mlodego', $get('telefon_panny'));
                        } elseif ($state === 'groom') {
                            // Uzupełniamy dane umowy z danych pana młodego
                            $set('umowa.imie', $get('imie2'));
                            $set('umowa.nazwisko', $get('nazwisko2'));
                            $set('umowa.telefon_mlodego', $get('telefon_pana'));
                        }
                    }),
                // Sekcja danych do umowy – ukryta do momentu wyboru osoby
                Forms\Components\Section::make('Dane Umowy')
                    ->description('Uzupełnij dane do tworzenia/aktualizacji umowy')
                    ->hidden(fn ($get) => !$get('contract_person'))
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('umowa.imie')
                                    ->label('Imię (umowa)')
                                    ->required(),
                                Forms\Components\TextInput::make('umowa.nazwisko')
                                    ->label('Nazwisko (umowa)')
                                    ->required(),
                                Forms\Components\TextInput::make('umowa.telefon_mlodego')
                                    ->label('Telefon (umowa)')
                                    ->required(),
                                Forms\Components\TextInput::make('umowa.pesel')
                                    ->label('Pesel')
                                    ->required()
                                    ->length(11)
                                    ->nullable(),
                                Forms\Components\TextInput::make('umowa.nr_dowodu')
                                    ->label('Nr dowodu')
                                    ->required(),
                                Forms\Components\TextInput::make('umowa.adres')
                                    ->label('Adres (umowa)')
                                    ->required(),
                                Forms\Components\TextInput::make('umowa.stawka')
                                    ->label('Stawka')
                                    ->numeric()
                                    ->required(),
                                Forms\Components\Select::make('umowa.pakiet')
                                    ->label('Pakiet (umowa)')
                                    ->options([
                                        'film' => 'Film',
                                        'foto' => 'Foto',
                                        'foto+film' => 'Foto+Film',
                                        'foto+film+fotoplener' => 'Foto+Film+Fotoplener',
                                        'foto+fotoplener' => 'Foto+Fotoplener',
                                    ])
                                    ->default(fn (callable $get) => $get('pakiet'))
                                    ->disabled(),
                                Forms\Components\DatePicker::make('umowa.data')
                                    ->label('Data (umowa)')
                                    ->default(fn (callable $get) => $get('data'))
                                    ->disabled(),
                                Forms\Components\TextInput::make('umowa.sala')
                                    ->label('Sala (umowa)')
                                    ->default(fn (callable $get) => $get('sala'))
                                    ->disabled(),
                                Forms\Components\TextInput::make('umowa.koscol')
                                    ->label('Kościół (umowa)')
                                    ->default(fn (callable $get) => $get('koscol'))
                                    ->disabled(),
                                Forms\Components\Toggle::make('umowa.dron')
                                    ->label('Dron')
                                    ->default(false),
                            ]),
                    ]),
                // Inne sekcje formularza (Strefa Dodatkowa, Podgląd zdjęcia, itd.) pozostają bez zmian
                Forms\Components\Section::make('Strefa Dodatkowa')
                    ->description('Dodatkowe informacje i szczegóły')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('liczba_gosci')
                                    ->label('Liczba Gości')
                                    ->numeric()
                                    ->default(0),
                                Forms\Components\Select::make('typ_wesela')
                                    ->label('Typ Wesela')
                                    ->options([
                                        'boho' => 'Boho',
                                        'klasyczny' => 'Klasyczny',
                                        'plenerowy' => 'Plenerowy',
                                    ])
                                    ->default('boho'),
                                Forms\Components\TextInput::make('mail')
                                    ->label('Email')
                                    ->email()
                                    ->placeholder('podaj adres email')
                                    ->nullable(),
                                Forms\Components\TextInput::make('social_link')
                                    ->label('Link do social')
                                    ->url()
                                    ->placeholder('np. https://facebook.com/twojprofil')
                                    ->nullable(),
                                Forms\Components\Toggle::make('pawel_jest')
                                    ->label('Paweł jest')
                                    ->default(false),
                            ]),
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
                    ]),
                    Forms\Components\Actions::make([
                        Forms\Components\Actions\Action::make('Pobierz PDF')
                            ->label('Generuj Umowę PDF')
                            ->url(function ($record) {
                                if (!$record) {
                                    return null;
                                }
                                // Pobieramy umowę na podstawie wedding_id
                                $umowa = \App\Models\Umowa::where('wedding_id', $record->id)->first();
                                return $umowa ? route('umowa.pdf', $umowa->id) : null;
                            })
                            ->hidden(function ($record) {
                                if (!$record) {
                                    return true;
                                }
                                // Ukryj przycisk, jeżeli nie ma powiązanej umowy
                                return !\App\Models\Umowa::where('wedding_id', $record->id)->exists();
                            })
                            ->openUrlInNewTab()
                            ->icon('heroicon-o-folder-arrow-down'),
                    ]),
                    
                    
                Forms\Components\Section::make('Podgląd zdjęcia')
                    ->hidden(fn ($record) => !$record || !$record->photo)
                    ->schema([
                        Forms\Components\Placeholder::make('photo_preview')
                            ->content(fn ($record) => new \Illuminate\Support\HtmlString(
                                '<img src="' . asset('storage/' . $record->photo) . '" alt="Zdjęcie pary" 
                                style="max-width: 40%; border-radius: 10px; box-shadow: 0px 4px 6px rgba(0,0,0,0.1);">'
                            ))
                            ->disableLabel(),
                        Forms\Components\Actions::make([
                            \Filament\Forms\Components\Actions\Action::make('delete_photo')
                                ->label('Usuń zdjęcie')
                                ->color('danger')
                                ->icon('heroicon-o-trash')
                                ->requiresConfirmation()
                                ->action(function ($record) {
                                    if ($record->photo) {
                                        \Illuminate\Support\Facades\Storage::delete('public/' . $record->photo);
                                        $record->photo = null;
                                        $record->save();
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
                Tables\Columns\TextColumn::make('typ_zamowienia')
                    ->label('Typ Zamówienia')
                    ->color(fn ($state) => $state == "rezerwacja" ? 'danger' : 'default'),
            ])
            ->defaultSort('created_at', 'desc')
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
