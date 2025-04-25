<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UmowaResource\Pages;
use App\Filament\Resources\UmowaResource\RelationManagers;
use App\Models\Umowa;
use App\Models\Wedding;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Actions\Action; // Add this line
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use GrahamCampbell\ResultType\Success;
use Illuminate\Support\Facades\Mail;
use App\Mail\UmowaLinkMail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;

class UmowaResource extends Resource
{
    protected static ?string $model = Umowa::class;

    protected static ?string $navigationIcon = 'heroicon-o-paper-clip';
    protected static ?string $navigationLabel = 'Umowy';
    protected static ?string $navigationGroup = 'Admin';
    protected static ?string $modelLabel = 'Panel Umów';
    protected static ?string $pluralModelLabel = 'Panel Umów';

    public static function form(Form $form): Form
{
    // Jeśli wedding_id jest przekazane, pobierz dane wesela
    $weddingId = request()->query('wedding_id');
    $wedding = $weddingId ? Wedding::find($weddingId) : null;

    return $form
        ->schema([
            // Jeśli mamy wedding_id, ukrywamy wybór wesela, w przeciwnym wypadku wyświetlamy select
            $wedding
                ? Forms\Components\Hidden::make('wedding_id')
                    ->default($weddingId)
                    ->dehydrated() // zapewnia, że wartość jest wysyłana wraz z formularzem
                : Forms\Components\Select::make('wedding_id')
                    ->label('Para Młoda')
                    ->options(function () {
                        return Wedding::where('typ_zamowienia', 'rezerwacja')
                            ->get()
                            ->mapWithKeys(function ($wedding) {
                                return [
                                    $wedding->id => "{$wedding->imie1} & {$wedding->imie2} - {$wedding->data}"
                                ];
                            });
                    })
                    ->required()
                    ->searchable()
                    ->reactive()
                    ->afterStateUpdated(function (callable $set, $state) {
                        if ($state) {
                            $wedding = Wedding::find($state);
                            if ($wedding) {
                                // Automatyczne wypełnienie powiązanych danych: sala, koscol, data i pakiet
                                $set('sala', $wedding->sala);
                                $set('koscol', $wedding->koscol);
                                $set('data', $wedding->data);
                                $set('pakiet', $wedding->pakiet);
                            }
                        }
                    }),
            Forms\Components\Card::make()
                ->schema([
                    Forms\Components\Grid::make(2)
                        ->schema([
                            Forms\Components\TextInput::make('imie')
                                ->label('Imię')
                                ->required(),

                            Forms\Components\TextInput::make('nazwisko')
                                ->label('Nazwisko')
                                ->required(),

                            Forms\Components\TextInput::make('pesel')
                                ->label('PESEL')
                                ->maxLength(11)
                                ->minLength(11)
                                ->required(),

                            Forms\Components\TextInput::make('nr_dowodu')
                                ->label('Nr dowodu')
                                ->required(),

                            Forms\Components\Textarea::make('adres')
                                ->label('Adres')
                                ->required(),

                            // Zamiast telefonów, ustawiamy pola sala, koscol, data i pakiet
                            Forms\Components\TextInput::make('sala')
                                ->label('Sala weselna')
                                ->default($wedding ? $wedding->sala : null)
                                ->required(),

                            Forms\Components\TextInput::make('koscol')
                                ->label('Kościół')
                                ->default($wedding ? $wedding->koscol : null)
                                ->required(),

                            Forms\Components\DatePicker::make('data')
                                ->label('Data ślubu')
                                ->default($wedding ? $wedding->data : null)
                                ->required(),

                            Forms\Components\Select::make('pakiet')
                                ->label('Pakiet')
                                ->default($wedding ? $wedding->pakiet : null)
                                ->required()
                                ->options([
                                    'foto'             => 'Fotografia',
                                    'film'             => 'Film',
                                    'foto_film'        => 'Fotografia + Film',
                                    'foto_film_plener' => 'Fotografia + Film + Plener',
                                    'foto_plener'      => 'Fotografia + Plener',
                                ])
                                ->searchable(),

                            Forms\Components\DatePicker::make('data_final')
                                ->label('Data finalizacji'),

                            Forms\Components\TextInput::make('stawka')
                                ->label('Stawka (PLN)')
                                ->numeric()
                                ->required(),

                            Forms\Components\TextInput::make('zadatek')
                                ->label('Zadatek (PLN)')
                                ->numeric(),

                            Forms\Components\Toggle::make('dron')
                                ->label('Dron')
                                ->default(false),
                        ]),
                ]),
            Forms\Components\Card::make()
                ->schema([
                    FileUpload::make('plik_umowy')
                        ->label('Plik umowy')
                        ->disk('public')
                        ->directory('umowy')
                        ->visibility('public')
                        ->previewable()
                        ->downloadable()
                        ->deletable(),
                    
                    Forms\Components\Actions::make([
                        Action::make('delete_contract')
                            ->label('Usuń plik umowy z dysku')
                            ->color('danger')
                            ->icon('heroicon-o-trash')
                            ->requiresConfirmation()
                            ->modalHeading('Potwierdzenie usunięcia')
                            ->modalSubheading('Czy na pewno chcesz usunąć plik umowy? Ta operacja jest nieodwracalna.')
                            ->modalButton('Tak, usuń plik')
                            ->hidden(fn ($record) => !$record || !$record->plik_umowy)
                            ->action(function ($record) {
                                if ($record->plik_umowy) {
                                    // Usuwa plik z dysku
                                    Storage::delete('public/' . $record->plik_umowy);
                                    $record->plik_umowy = null;
                                    $record->save();
                                }
                            })
                    ]),
                    
                    Forms\Components\DatePicker::make('data_podpisania')
                        ->label('Data podpisania'),
                    
                    Forms\Components\Checkbox::make('status')
                        ->label('Podpisana')
                        ->default(false)
                        ->afterStateHydrated(function ($state, $component) {
                            $component->state($state == 'podpisana');
                        })
                        ->dehydrateStateUsing(function ($state) {
                            return $state ? 'podpisana' : 'utworzona';
                        }),
                ]),
            Forms\Components\Actions::make([
                Forms\Components\Actions\Action::make('Pobierz PDF')
                    ->label('Generuj Umowę PDF')
                    ->url(fn ($record) => $record ? route('umowa.pdf', $record->id) : null)
                    ->hidden(fn ($record) => $record === null)
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-folder-arrow-down'),
            ]),
            // Pole na adres email odbiorcy – nie jest zapisywane w bazie
            Forms\Components\TextInput::make('recipient_email')
                ->label('Email odbiorcy')
                ->columnSpanFull()
                ->email(),
            // Akcja wysyłki formularza umowy na podany email
            Forms\Components\Actions::make([
                Forms\Components\Actions\Action::make('sendEmail')
                    ->label('Wyślij formularz umowy')
                    ->color('success')
                    ->action(function (array $data, $livewire): void {
                        $recipient = $livewire->data['recipient_email'] ?? null;
                        if (!$recipient) {
                            throw new \Exception("Brakuje adresu e-mail odbiorcy.");
                        }
                        
                        // Pobierz wedding_id – zarówno dla edycji jak i tworzenia nowego rekordu
                        $weddingId = $livewire->record
                            ? $livewire->record->wedding_id
                            : $livewire->data['wedding_id'] ?? null;
                        
                        if (!$weddingId) {
                            throw new \Exception("Nie można znaleźć ID wesela. Upewnij się, że formularz został prawidłowo wypełniony.");
                        }
                        
                        // Generujemy link do umowy
                        $link = URL::signedRoute('umowa.show', ['wedding_id' => $weddingId]);
                        $link = str_replace('admin.salemtest2.you2.pl', 'salemtest2.you2.pl', $link); // CHWILOWO ZMIENIA NA PORT 3000 RECZNIE !!!!!!!! DO ZMIANY 
                        
                        Mail::to($recipient)->send(new UmowaLinkMail($link));
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Email został wysłany pomyślnie')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->icon('heroicon-o-envelope'),
            ]),
        ]);
}


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('wedding.imie1')
                    ->label('Pan Młody')
                    ->searchable(),
                Tables\Columns\TextColumn::make('wedding.imie2')
                    ->label('Panna Młoda')
                    ->searchable(),
                Tables\Columns\TextColumn::make('wedding.data')
                    ->label('Data wesela')
                    ->date(),
                Tables\Columns\TextColumn::make('imie')
                    ->label('Imię')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nazwisko')
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'primary' => 'utworzona',
                        'success' => 'podpisana',
                        'danger' => 'anulowana',
                    ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'utworzona' => 'Utworzona',
                        'podpisana' => 'Podpisana',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('Pobierz pdf')
                    ->url(fn (Umowa $record) => route('umowa.pdf', $record->id))
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-folder-arrow-down'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('status', 'asc'); 
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUmowas::route('/'),
            'create' => Pages\CreateUmowa::route('/create'),
            'edit' => Pages\EditUmowa::route('/{record}/edit'),
        ];
    }
}
