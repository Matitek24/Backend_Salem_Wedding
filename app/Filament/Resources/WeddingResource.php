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

class WeddingResource extends Resource
{
    protected static ?string $model = Wedding::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Wesela';
    protected static ?string $navigationGroup = 'Admin';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\TextInput::make('imie1')->label('Imię Panny Młodej')->required(),
            Forms\Components\TextInput::make('imie2')->label('Imię Pana Młodego')->required(),
            Forms\Components\DatePicker::make('data')->label('Data Wesela')->required(),
            Forms\Components\Select::make('typ_wesela')
                ->label('Typ Wesela')
                ->options([
                    'boho' => 'Boho',
                    'klasyczny' => 'Klasyczny',
                    'plenerowy' => 'Plenerowy',
                ])
                ->default('boho'),
            Forms\Components\TextInput::make('sala')->label('Sala Weselna')->default(''),
            Forms\Components\TextInput::make('koscol')->label('Kościół')->default(''),
            Forms\Components\TextInput::make('liczba_gosci')->label('Liczba Gości')->numeric()->default(0),
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
                Tables\Columns\TextColumn::make('liczba_gosci')
                    ->label('Liczba Gości')
                    ->sortable()
                    ->color(fn ($state) => $state == 0 ? 'danger' : 'default'), 
            ])
            ->filters([])
            ->actions([
                Tables\Actions\Action::make('export')
                    ->label('Eksportuj do Excela')
                    ->url(route('weddings.export'))
                    ->openUrlInNewTab(), // otwiera w nowej karcie, aby pobranie pliku się powiodło
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
