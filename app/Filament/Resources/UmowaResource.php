<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UmowaResource\Pages;
use App\Filament\Resources\UmowaResource\RelationManagers;
use App\Models\Umowa;
use App\Models\Wedding;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use GrahamCampbell\ResultType\Success;
use Illuminate\Support\Facades\Mail;
use App\Mail\UmowaLinkMail; // Twój Mailable, który tworzy maila z linkiem

class UmowaResource extends Resource
{
    protected static ?string $model = Umowa::class;

    protected static ?string $navigationIcon = 'heroicon-o-paper-clip';
    protected static ?string $navigationLabel = 'Umowy';
    protected static ?string $navigationGroup = 'Admin';

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
                                      // Automatyczne wypełnienie danych
                                      $set('telefon_mlodego', $wedding->telefon_pana);
                                      $set('telefon_mlodej', $wedding->telefon_panny);
                                      $set('sala', $wedding->sala);
                                      $set('koscol', $wedding->koscol);
                                  }
                              }
                          }),
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('sala')
                                    ->label('Sala weselna')
                                    ->dehydrated(true)
                                    ->default($wedding ? $wedding->sala : null),
                                Forms\Components\TextInput::make('koscol')
                                    ->label('Kościół')
                                    ->dehydrated(true)
                                    ->default($wedding ? $wedding->koscol : null),
                            ]),
                        Forms\Components\TextInput::make('imie')
                            ->label('Imię')
                            ->required(),
                        Forms\Components\TextInput::make('nazwisko')
                            ->required(),
                        Forms\Components\TextInput::make('pesel')
                            ->label('PESEL')
                            ->maxLength(11),
                        Forms\Components\TextInput::make('nr_dowodu')
                            ->label('Nr dowodu'),
                        Forms\Components\Textarea::make('adres')
                            ->rows(3)
                            ->required(),
                        Forms\Components\TextInput::make('nip')
                            ->label('NIP'),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('telefon_mlodego')
                                    ->label('Telefon Pana Młodego')
                                    ->default($wedding ? $wedding->telefon_pana : null)
                                    ->required(),
                                Forms\Components\TextInput::make('telefon_mlodej')
                                    ->label('Telefon Pani Młodej')
                                    ->default($wedding ? $wedding->telefon_panny : null)
                                    ->required(),
                            ]),
                    ]),
                    
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\FileUpload::make('plik_umowy')
                            ->label('Plik umowy')
                            ->disk('public')
                            ->directory('umowy'),
                        Forms\Components\DatePicker::make('data_podpisania')
                            ->label('Data podpisania'),
                        Forms\Components\Select::make('status')
                            ->options([
                                'utworzona' => 'Utworzona',
                                'podpisana' => 'Podpisana',
                                'anulowana' => 'Anulowana',
                            ])
                            ->default('utworzona')
                            ->required(),
                    ]),
                      // Akcja generowania PDF
                Forms\Components\Actions::make([
                    Forms\Components\Actions\Action::make('Pobierz PDF')
                        ->label('Generuj Umowę PDF')
                        ->url(fn ($record) => route('umowa.pdf', $record->id))
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
                        // Pobieramy email bezpośrednio z aktualnego stanu formularza
                        $recipient = $livewire->data['recipient_email'] ?? null;
                        
                        if (!$recipient) {
                            throw new \Exception("Brakuje adresu e-mail odbiorcy.");
                        }
                        
                        // Pobieramy wedding_id z aktualnego formularza
                        // Dla edycji istniejącego rekordu
                        if ($livewire->record) {
                            $weddingId = $livewire->record->wedding_id;
                        }
                        // Dla tworzenia nowego rekordu - pobierz z danych formularza
                        else {
                            $weddingId = $livewire->data['wedding_id'] ?? null;
                        }
                        
                        if (!$weddingId) {
                            throw new \Exception("Nie można znaleźć ID wesela. Upewnij się, że formularz został prawidłowo wypełniony.");
                        }
                        
                        // Generujemy link do umowy
                        $link = url("/umowa/{$weddingId}");
                        
                        // Wysyłamy maila
                        Mail::to($recipient)->send(new UmowaLinkMail($link));
                        
                        // Dodajemy powiadomienie o sukcesie
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
                        'anulowana' => 'Anulowana',
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
            ]);
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
