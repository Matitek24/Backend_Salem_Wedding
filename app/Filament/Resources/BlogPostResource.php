<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogPostResource\Pages;
use App\Models\BlogPost;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class BlogPostResource extends Resource
{
    protected static ?string $model = BlogPost::class;

    protected static ?string $navigationLabel = 'Blog';
    protected static ?string $pluralModelLabel = 'Blog List';
    protected static ?string $modelLabel = 'Blog List';
    protected static ?string $navigationIcon = 'heroicon-o-identification';
    protected static ?string $navigationGroup = 'CMS Zarządzanie';
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Podstawowe informacje')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Tytuł')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\FileUpload::make('image')
                            ->label('Zdjęcie')
                            ->image()
                            ->directory('blog-images')
                            ->visibility('public'),
                        
                        Forms\Components\Textarea::make('short_description')
                            ->label('Krótki opis')
                            ->required()
                            ->rows(3),
                        
                        // Pole publikacji
                        Forms\Components\Toggle::make('is_published')
                            ->label('Opublikowany')
                            ->default(false),
                        
                        // Nowe pole "Główne"
                        Forms\Components\Toggle::make('is_main')
                            ->label('Główne')
                            ->default(false),
                        
                        Forms\Components\DateTimePicker::make('published_at')
                            ->label('Data publikacji')
                            ->default(now()),
                    ]),
                
                Forms\Components\Section::make('Treść')
                    ->schema([
                        Forms\Components\RichEditor::make('content')
                            ->label('Zawartość bloga')
                            ->required()
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('blog-content-images')
                            ->toolbarButtons([
                                'attachFiles',
                                'blockquote',
                                'bold',
                                'bulletList',
                                'codeBlock',
                                'h2',
                                'h3',
                                'italic',
                                'link',
                                'orderedList',
                                'redo',
                                'strike',
                                'underline',
                                'undo',
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Tytuł')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('short_description')
                    ->label('Krótki opis')
                    ->limit(50),
                
                Tables\Columns\IconColumn::make('is_published')
                    ->label('Opublikowany')
                    ->boolean(),
                
                // Dodana kolumna "Główne"
                Tables\Columns\IconColumn::make('is_main')
                    ->label('Główne')
                    ->boolean(),
                
                Tables\Columns\TextColumn::make('published_at')
                    ->label('Data publikacji')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Utworzono')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Zaktualizowano')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('published')
                    ->label('Tylko opublikowane')
                    ->query(fn ($query) => $query->where('is_published', true)),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListBlogPosts::route('/'),
            'create' => Pages\CreateBlogPost::route('/create'),
            'edit' => Pages\EditBlogPost::route('/{record}/edit'),
        ];
    }
}
