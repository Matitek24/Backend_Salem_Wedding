<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestimonialResource\Pages;
use App\Models\Testimonial;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TestimonialResource extends Resource
{
    protected static ?string $model = Testimonial::class;

    protected static ?string $navigationLabel = 'Opinie klientów';
    protected static ?string $pluralModelLabel = 'Opinie klientów';
    protected static ?string $modelLabel = 'Opinia klienta';
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationGroup = 'CMS Zarządzanie';
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informacje o opinii')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Imię pary')
                            ->placeholder('np. Aleksandra & Łukasz')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\FileUpload::make('image')
                            ->label('Zdjęcie')
                            ->image()
                            ->imageCropAspectRatio('3:2')
                            ->imageResizeMode('cover')
                            ->directory('testimonials-images')
                            ->visibility('public')
                            ->required(),
                        
                        Forms\Components\Select::make('image_position')
                            ->label('Pozycja zdjęcia')
                            ->options([
                                'left' => 'Po lewej stronie',
                                'right' => 'Po prawej stronie',
                            ])
                            ->default('left')
                            ->required(),

                        Forms\Components\Toggle::make('is_featured')
                            ->label('Wyróżniona opinia')
                            ->default(false),
                        
                        Forms\Components\TextInput::make('order')
                            ->label('Kolejność wyświetlania')
                            ->numeric()
                            ->default(0),
                    ]),
                
                Forms\Components\Section::make('Treść opinii')
                    ->schema([
                        Forms\Components\Textarea::make('content')
                            ->label('Tekst opinii')
                            ->required()
                            ->rows(6),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Imię pary')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('image_position')
                    ->label('Pozycja zdjęcia')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'left' => 'primary',
                        'right' => 'success',
                    }),
                
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Wyróżniona')
                    ->boolean(),
                
                Tables\Columns\TextColumn::make('order')
                    ->label('Kolejność')
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
                Tables\Filters\SelectFilter::make('image_position')
                    ->label('Pozycja zdjęcia')
                    ->options([
                        'left' => 'Po lewej',
                        'right' => 'Po prawej',
                    ]),
                
                Tables\Filters\Filter::make('is_featured')
                    ->label('Tylko wyróżnione')
                    ->query(fn ($query) => $query->where('is_featured', true)),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('order', 'asc');
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
            'index' => Pages\ListTestimonials::route('/'),
            'create' => Pages\CreateTestimonial::route('/create'),
            'edit' => Pages\EditTestimonial::route('/{record}/edit'),
        ];
    }
}