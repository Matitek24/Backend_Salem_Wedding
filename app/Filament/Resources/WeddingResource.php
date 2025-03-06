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
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
        ])
        ->filters([])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\Action::make('quickAddWedding')
                ->label('Szybkie wesele')
                ->icon('heroicon-o-plus-circle')
                ->form([
                    \Filament\Forms\Components\TextInput::make('imie1')->label('Imię Panny Młodej')->required(),
                    \Filament\Forms\Components\TextInput::make('imie2')->label('Imię Pana Młodego')->required(),
                    \Filament\Forms\Components\DatePicker::make('data')->label('Data Wesela')->required(),
                ])
                ->action(function (array $data) {
                    Wedding::create($data);
                })
                ->successNotificationTitle('Wesele dodane!'),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
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
