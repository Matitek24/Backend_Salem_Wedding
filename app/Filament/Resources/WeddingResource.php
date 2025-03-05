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
                Forms\Components\DatePicker::make('date')
                    ->label('Data Wesela')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label('Data Wesela')
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListWeddings::route('/'),
            'create' => Pages\CreateWedding::route('/create'),
            'edit' => Pages\EditWedding::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            WeddingCalendar::class, // Dodajemy kalendarz do zakładki Weddings
        ];
    }
}
