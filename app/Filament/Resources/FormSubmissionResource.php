<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FormSubmissionResource\Pages;
use App\Models\FormSubmission;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table as FilamentTable;

class FormSubmissionResource extends Resource
{
    protected static ?string $model = FormSubmission::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Zgłoszenia';
    protected static ?string $navigationGroup = 'Admin';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('first_name')
                    ->label('Imię')
                    ->required(),
                Forms\Components\TextInput::make('last_name')
                    ->label('Nazwisko')
                    ->required(),
            ]);
    }

    public static function table(FilamentTable $table): FilamentTable
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('first_name')->label('Imię')->searchable(),
                Tables\Columns\TextColumn::make('last_name')->label('Nazwisko')->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Data zgłoszenia')
                    ->dateTime('d-m-Y H:i')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('export')
                    ->label('Eksportuj do Excela')
                    ->url(route('form_submissions.export'))
                    ->openUrlInNewTab(), // otwiera w nowej karcie, aby pobranie pliku się powiodło
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListFormSubmissions::route('/'),
            'create' => Pages\CreateFormSubmission::route('/create'),
            'edit'   => Pages\EditFormSubmission::route('/{record}/edit'),
        ];
    }
}
