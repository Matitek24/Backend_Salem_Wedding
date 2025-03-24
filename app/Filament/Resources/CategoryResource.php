<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\GalleryImagesRelationManagerResource\RelationManagers\GalleryImagesRelationManager;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\Layout\Grid;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static ?string $navigationLabel = 'Portfolio';
    protected static ?string $pluralModelLabel = 'Kategorie';
    protected static ?string $modelLabel = 'Kategoria';
    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationGroup = 'CMS Zarządzanie';
// formularz do kategorii
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255)
                ->label('Nazwa kategorii'),
        ]);
    }
// tabela do kategorii 
    public static function table(Table $table): Table
    {
        return $table
            ->contentGrid([
                'sm' => 2, 
                'md' => 3,
                'lg' => 3,
                'xl' => 3,
            ])
            ->columns([
              Grid::make(1)
                ->schema([
                    Tables\Columns\TextColumn::make('name')
                    ->label('Kategoria'),
                ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
// relacje do galerii
    public static function getRelations(): array
    {
        return [
            GalleryImagesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}