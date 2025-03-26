<?php
namespace App\Filament\Resources;

use App\Filament\Resources\FormSubmissionResource\Pages;
use App\Models\FormSubmission;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table as FilamentTable;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;

class FormSubmissionResource extends Resource
{
    protected static ?string $model = FormSubmission::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Zapytania Klientów';
    protected static ?string $navigationGroup = 'Admin';
    protected static ?string $modelLabel = 'Baza Klientów';
    protected static ?string $pluralModelLabel = 'Baza Klientów';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Section::make('Dane osobowe')
                    ->description('Informacje o kliencie')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('first_name')
                                    ->label('Imię')
                                    ->required()
                                    ->maxLength(100),
                                
                                Forms\Components\TextInput::make('email')
                                    ->label('Adres e-mail')
                                    ->email()
                                    ->required()
                                    ->maxLength(255),
                                    
                                Forms\Components\TextInput::make('miejscowosc')
                                    ->label('Miejscowosc')
                                    ->required()
                                    ->maxLength(255),
                            ]),
                    ]),
                
                Section::make('Szczegóły wesela')
                    ->description('Informacje o uroczystości')
                    ->icon('heroicon-o-calendar')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\DatePicker::make('wedding_date')
                                    ->label('Data wesela')
                                    ->required()
                                    ->displayFormat('d.m.Y'),
                                
                                    Forms\Components\Select::make('pakiet')
                                    ->label('Pakiet')
                                    ->options([
                                        'film' => 'Film',
                                        'foto' => 'Foto',
                                        'foto+film' => 'Foto+Film',
                                        'foto+film+fotoplener' => 'Foto+Film+Fotoplener',
                                        'foto+fotoplener' => 'Foto+Fotoplener',
                                    ]),
                            ]),
                    ]),
                
            ]);
    }

    public static function table(FilamentTable $table): FilamentTable
    {
        return $table
        
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('first_name')
                    ->label('Imię')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('miejscowosc')
                    ->label("Miejscowosc")
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('wedding_date')
                    ->label('Data wesela')
                    ->date('d.m.Y')
                    ->sortable()
                    ->searchable(),
                
                Tables\Columns\BadgeColumn::make('pakiet')
                    ->label('Pakiet')
                    ->colors([
                        'primary' => 'podstawowy',
                        'secondary' => 'standardowy',
                        'success' => 'premium',
                        'warning' => 'custom',
                    ])
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Data zgłoszenia')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            
            ->actions([
                Tables\Actions\Action::make('export')
                    ->label('Eksportuj do Excela')
                    ->url(route('form_submissions.export'))
                    ->icon('heroicon-o-calendar')
                    ->color('success')
                    ->openUrlInNewTab(),
                    
                Tables\Actions\ViewAction::make()
                    ->label('Podgląd'),
                    
                Tables\Actions\EditAction::make()
                    ->label('Edytuj'),
                    
                Tables\Actions\DeleteAction::make()
                    ->label('Usuń'),
            ])
     
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->searchable();
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFormSubmissions::route('/'),
            'create' => Pages\CreateFormSubmission::route('/create'),
            'edit' => Pages\EditFormSubmission::route('/{record}/edit'),
        ];
    }
}